from fastapi import FastAPI, Query
from fastapi.responses import JSONResponse
import mysql.connector
import os

app = FastAPI()

DB_CONFIG = {
    "host": os.getenv("DB_HOST", "db"),
    "user": os.getenv("DB_USER", "admin"),
    "password": os.getenv("DB_PASSWORD", "123"),
    "database": os.getenv("DB_NAME", "grocery_db")
}

def get_db():
    return mysql.connector.connect(**DB_CONFIG)

@app.get("/categories")
def get_categories():
    conn = get_db()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT id, name, slug FROM categories")
    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return result

@app.get("/products")
def get_products(page: int = 1, limit: int = 20, category_id: int = None):
    offset = (page - 1) * limit
    conn = get_db()
    cursor = conn.cursor(dictionary=True)

    if category_id:
        cursor.execute("""
            SELECT p.id, p.name, p.price_cents, p.display_price, p.image_url, c.name AS category
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = %s
            LIMIT %s OFFSET %s
        """, (category_id, limit, offset))
    else:
        cursor.execute("""
            SELECT p.id, p.name, p.price_cents, p.display_price, p.image_url, c.name AS category
            FROM products p
            JOIN categories c ON p.category_id = c.id
            LIMIT %s OFFSET %s
        """, (limit, offset))

    result = cursor.fetchall()
    cursor.close()
    conn.close()
    return result

@app.get("/products/{product_id}")
def get_product(product_id: int):
    conn = get_db()
    cursor = conn.cursor(dictionary=True)
    cursor.execute("""
        SELECT p.id, p.name, p.brand, p.description, p.price_cents, p.display_price, p.uom,
               p.package_size, p.image_url, c.name AS category
        FROM products p
        JOIN categories c ON p.category_id = c.id
        WHERE p.id = %s
    """, (product_id,))
    result = cursor.fetchone()
    cursor.close()
    conn.close()
    if not result:
        return JSONResponse(content={"error": "Product not found"}, status_code=404)
    return result