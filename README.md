# WooCommerce Custom Payment Gateway - Classic Bank Transfer

A simple, manual WooCommerce payment gateway that allows customers to pay via direct bank transfer or any custom offline method. Built with compatibility for Dokan multivendor marketplace.

---

## 🧾 Features

- Adds a custom payment gateway to WooCommerce checkout.
- Lets you define bank transfer instructions.
- Manual payment handling (status set to "On-Hold").
- Easy to configure from WooCommerce settings.
- Dokan-compatible (global gateway visible to all vendors).

---

## 🔧 Installation

1. Download or clone this repository into your WordPress plugins directory:

2. Make sure your folder contains:

3. Go to **WordPress Admin > Plugins** and activate **Custom Payment Gateway**.

4. Go to **WooCommerce > Settings > Payments**.

5. Enable **Classic Bank Transfer** and configure title, description, and instructions.

---

## 🛍️ How It Works

- At checkout, customers can choose "Direct Bank Transfer".
- After placing an order, they will see instructions to complete the payment manually.
- The order is set to **On-Hold** status until the admin confirms payment.
- Admin can later mark the order as **Processing** or **Completed** manually.