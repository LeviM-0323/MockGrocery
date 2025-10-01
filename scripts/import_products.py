# This script is used to insert product data from a CSV to the database
import unicodedata
import pandas as pd
import mysql.connector
import re
import json

# Replace with your database credentials
DB_HOST = "db"
DB_USER = "admin"
DB_PASSWORD = "123"
DB_NAME = "grocery_db"

# Must be one of the CSV files from the set provided
df = pd.read_csv("./grocery_data_aug_2025.csv").head(500)

# Awful category rules (gonna use them anyways)
CATEGORY_RULES = {
    "Produce": [
        r"\b(apple|banana|berry|strawberry|blueberry|grape|tomato|lettuce|spinach|melon|cucumber|pepper|carrot|onion|garlic|potato|avocado|broccoli|cauliflower|kale|cabbage|asparagus|mushroom|pear|orange|lemon|lime|peach|plum)\b"
    ],
    "Bakery": [
        r"\b(bread|bun|roll|bagel|loaf|croissant|muffin|donut|bap)\b"
    ],
    "Dairy": [
        r"\b(milk|cheese|butter|yogurt|cream|kefir|cheddar|mozzarella|parmesan|cottage|dairy)\b"
    ],
    "Meat": [
        r"\b(chicken|beef|pork|ham|turkey|lamb|bacon|sausage|hot[\s-]?dog|wiener|salami|deli|steak|ground\s(beef|pork))\b"
    ],
    "Frozen": [
        r"\b(frozen|ice cream|ice-cream|popsicle|veggie burger|french fries|frites)\b"
    ],
    "Snacks": [
        r"\b(chips|cookies|crackers|popcorn|pretzel|granola bar|candy|chocolate|biscuit)\b"
    ],
    "Beverages": [
        r"\b(juice|soda|coffee|tea|water|cola|energy drink|sparkling water|iced tea|lemonade|smoothie)\b"
    ]
}

def slugify(text):
    text = unicodedata.normalize("NFKD", text).encode("ascii", "ignore").decode("ascii")
    text = re.sub(r"[^a-zA-Z0-9]+", "-", text).strip("-").lower() # <-- Spell of slugification
    return text

# The following is my futile atempt at saving time categorizing items over doing it manually
def detect_category(name, description=""):
    title = (name or "").lower()
    desc = (description or "").lower()

    # remove punctuation so patterns like \bword\b behave consistently
    title = re.sub(r"[^\w\s]", " ", title)
    desc = re.sub(r"[^\w\s]", " ", desc)

    TITLE_WEIGHT = 10
    DESC_WEIGHT = 1

    scores = {cat: 0 for cat in CATEGORY_RULES}
    title_matches = {cat: 0 for cat in CATEGORY_RULES}
    total_matches = {cat: 0 for cat in CATEGORY_RULES}
    earliest_pos = {cat: None for cat in CATEGORY_RULES}

    for cat, patterns in CATEGORY_RULES.items():
        for pattern in patterns:
            # count occurrences
            t_count = len(re.findall(pattern, title, flags=re.IGNORECASE))
            d_count = len(re.findall(pattern, desc, flags=re.IGNORECASE))
            if t_count:
                # find earliest occurrence position in title for tie-breaker
                m = re.search(pattern, title, flags=re.IGNORECASE)
                if m:
                    pos = m.start()
                    if earliest_pos[cat] is None or pos < earliest_pos[cat]:
                        earliest_pos[cat] = pos
            scores[cat] += t_count * TITLE_WEIGHT + d_count * DESC_WEIGHT
            title_matches[cat] += t_count
            total_matches[cat] += (t_count + d_count)

    # choose best score
    best_score = max(scores.values())
    if best_score == 0:
        return "Other"

    # all categories that share best_score
    candidates = [c for c, s in scores.items() if s == best_score]
    if len(candidates) == 1:
        return candidates[0]

    # tie-breaker 1: prefer earliest match position in title
    candidates.sort(key=lambda c: (earliest_pos[c] if earliest_pos[c] is not None else float("inf")))
    top = candidates[0]

    # tie-breaker 2: prefer more title matches
    tied = [c for c in candidates if (earliest_pos[c] == earliest_pos[top])]
    if len(tied) == 1:
        return top
    tied.sort(key=lambda c: title_matches[c], reverse=True)
    top2 = tied[0]

    if title_matches[top2] > 0:
        return top2

    # tie-breaker 3: prefer more total matches
    tied.sort(key=lambda c: total_matches[c], reverse=True)
    if total_matches[tied[0]] > 0:
        return tied[0]

    # fallback
    return "Other"
# ... I probably should've just done it manually

def extract_image(img_data):
    try:
        images = json.loads(img_data.replace("'", '"'))
        if images and isinstance(images, list):
            return images[0].get("imageUrl", None)
    except:
        return None
    return None

conn = mysql.connector.connect(
    host=DB_HOST,
    user=DB_USER,
    password=DB_PASSWORD,
    database=DB_NAME
)
cursor = conn.cursor()

categories = list(CATEGORY_RULES.keys()) + ["Other"]
cat_map = {}

for cat in categories:
    slug = slugify(cat) # <-- Slugify the cat
    cursor.execute(
        "INSERT INTO categories (name, slug) VALUES (%s, %s) ON DUPLICATE KEY UPDATE name=VALUES(name)",
        (cat, slug) # <-- Now he's a cat-slug
    )
conn.commit()

cursor.execute("SELECT id, name FROM categories")
for cid, name in cursor.fetchall():
    cat_map[name] = cid

products = []
for _, row in df.iterrows():
    external_id = row["productId"]
    name = row["title"]
    brand = row.get("brand") if pd.notna(row.get("brand")) else None
    description = row.get("description") if pd.notna(row.get("description")) else None
    price = row["pricing.price"]
    price_cents = int(float(price) * 100)
    display_price = row["pricing.displayPrice"]
    uom = row["uom"]
    package_size = row["packageSizing"]
    image_url = extract_image(row["productImage"])
    category_name = detect_category(name, description or "")
    category_id = cat_map[category_name]

    products.append((
        external_id, name, brand, description, price_cents, display_price, uom, package_size, image_url, category_id
    ))

sql = """
INSERT INTO products (external_id, name, brand, description, price_cents, display_price, uom, package_size, image_url, category_id)
VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)
ON DUPLICATE KEY UPDATE name=VALUES(name), price_cents=VALUES(price_cents), category_id=VALUES(category_id);
"""

batch_size = 500
for i in range(0, len(products), batch_size):
    cursor.executemany(sql, products[i:i+batch_size])
    conn.commit()
    print(f"Inserted {i+batch_size if i+batch_size < len(products) else len(products)} rows")

cursor.close()
conn.close()
print("✅ Done importing products")
