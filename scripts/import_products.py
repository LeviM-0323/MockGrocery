# This script is used to insert product data from a CSV to the database
import unicodedata
import pandas as pd
import mysql.connector
import re
import json

# Replace with your database credentials
DB_HOST = "localhost"
DB_USER = "admin"
DB_PASSWORD = "123"
DB_NAME = "grocery_db"

# Must be one of the CSV files from the set provided
df = pd.read_csv("C:\\MockGrocery\\scripts\\grocery_data_aug_2025.csv")

CATEGORY_RULES = {
    "Produce": ["apple", "banana", "berry", "tomato", "lettuce", "melon", "cucumber", "pepper"],
    "Bakery": ["bread", "bun", "roll", "bagel"],
    "Dairy": ["milk", "cheese", "butter", "yogurt", "cream"],
    "Meat": ["chicken", "beef", "pork", "ham", "turkey"],
    "Frozen": ["frozen", "ice cream", "popsicle"],
    "Snacks": ["chips", "cookies", "crackers", "popcorn"],
    "Beverages": ["juice", "soda", "coffee", "tea", "water"]
}

def slugify(text):
    text = unicodedata.normalize("NFKD", text).encode("ascii", "ignore").decode("ascii")
    text = re.sub(r"[^a-zA-Z0-9]+", "-", text).strip("-").lower() # <-- Spell of slugification
    return text

def detect_category(name):
    lower_name = name.lower()
    for cat, keywords in CATEGORY_RULES.items():
        if any(kw in lower_name for kw in keywords):
            return cat
    return "Other"

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
    category_name = detect_category(name)
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