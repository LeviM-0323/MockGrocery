import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'

function App() {
  return (
    <div className="min-h-screen bg-gradient-to-br from-green-50 to-green-100">
      {/* Header */}
      <header className="bg-white shadow sticky top-0 z-10">
        <div className="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
          <div className="flex items-center gap-2">
            <span className="text-2xl font-bold text-green-700">MockGrocery</span>
          </div>
          <nav className="space-x-6">
            <a href="#" className="text-green-700 hover:underline">Shop</a>
            <a href="#" className="text-green-700 hover:underline">Deals</a>
            <a href="#" className="text-green-700 hover:underline">About</a>
            <a href="#" className="text-green-700 hover:underline">Sign In</a>
          </nav>
        </div>
      </header>

      {/* Hero Section */}
      <section className="max-w-6xl mx-auto px-4 py-16 flex flex-col md:flex-row items-center gap-10">
        <div className="flex-1">
          <h1 className="text-4xl md:text-5xl font-extrabold text-green-800 mb-4">
            Fresh Groceries, Delivered Fast
          </h1>
          <p className="text-lg text-green-900 mb-6">
            Shop fresh produce, bakery, dairy, and more. Enjoy fast delivery and great deals every day!
          </p>
          <a
            href="#"
            className="inline-block bg-green-600 text-white px-6 py-3 rounded-lg font-semibold shadow hover:bg-green-700 transition"
          >
            Start Shopping
          </a>
        </div>
        <div className="flex-1 flex justify-center">
          <img
            src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=400&q=80"
            alt="Groceries"
            className="rounded-2xl shadow-lg w-full max-w-xs"
          />
        </div>
      </section>

      {/* Features */}
      <section className="max-w-6xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-3 gap-8">
        <div className="bg-white rounded-xl shadow p-6 flex flex-col items-center">
          <span className="text-3xl mb-2">🥦</span>
          <h2 className="font-bold text-lg mb-1">Fresh Produce</h2>
          <p className="text-gray-600 text-center">Locally sourced fruits and vegetables delivered to your door.</p>
        </div>
        <div className="bg-white rounded-xl shadow p-6 flex flex-col items-center">
          <span className="text-3xl mb-2">⏱️</span>
          <h2 className="font-bold text-lg mb-1">Fast Delivery</h2>
          <p className="text-gray-600 text-center">Get your groceries in as little as 1 hour, right to your home.</p>
        </div>
        <div className="bg-white rounded-xl shadow p-6 flex flex-col items-center">
          <span className="text-3xl mb-2">💸</span>
          <h2 className="font-bold text-lg mb-1">Great Deals</h2>
          <p className="text-gray-600 text-center">Save more with weekly specials and exclusive online offers.</p>
        </div>
      </section>

      {/* Footer */}
      <footer className="bg-green-800 text-white py-6 mt-12">
        <div className="max-w-6xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
          <span>&copy; {new Date().getFullYear()} MockGrocery. All rights reserved.</span>
          <div className="space-x-4 mt-2 md:mt-0">
            <a href="#" className="hover:underline">Privacy Policy</a>
            <a href="#" className="hover:underline">Contact</a>
          </div>
        </div>
      </footer>
    </div>
  )
}

export default App
