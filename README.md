# IKémon Trading Platform

A browser-based Pokémon card trading platform where users can buy, sell, and collect cards. Built with vanilla PHP, JavaScript, and JSON-based file storage.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)

## Overview

IKémon is a multi-user trading card platform with role-based access control. Regular users browse, buy, and sell Pokémon cards, while admins manage the card catalog. All data is persisted using JSON files, with session-based authentication and server-side validation.

### Key Features

- **Card Marketplace** — Browse, filter by type, and purchase cards from the admin deck
- **User Accounts** — Registration with password validation, session-based authentication
- **Trading System** — Buy cards (up to 5) and sell back at 90% value
- **Admin Panel** — Create new cards with image upload and validation
- **Type Filtering** — Filter cards by Pokémon type (Electric, Water, Fire, Grass, etc.)
- **Dynamic Theming** — Card detail pages change colors based on Pokémon element type

## Project Structure

```
pokemon-trading-card/
├── index.php               # Main marketplace page
├── login.php               # User authentication
├── signup.php              # User registration
├── card_details.php        # Individual card view
├── user_details.php        # User profile and owned cards
├── buy_card.php            # Purchase handler
├── sell_card.php           # Sell handler
├── add_card.php            # Admin: create new card
├── upload.php              # Image upload handler
├── storage.php             # JSON file storage class
├── data/
│   ├── cards.json          # Card database
│   └── users.json          # User database
├── assets/
│   ├── Images/             # Card artwork (32 Pokémon)
│   └── Icons/              # UI icons (HP, Attack, Defense, Price)
├── styles/
│   ├── index.css           # Marketplace styles
│   ├── signup.css          # Auth form styles
│   ├── details.css         # Card detail page
│   ├── add_card.css        # Admin form styles
│   ├── user_details.css    # Profile page styles
│   └── alert.css           # Notification banner
└── scripts/
    └── form.js             # Form utilities and alerts
```

## Getting Started

### Prerequisites

- PHP 7.4+ with `json` extension
- A web server (Apache, Nginx) or PHP's built-in server

### Running Locally

```bash
git clone https://github.com/Jamolov-Isroilbek/pokemon-trading-card.git
cd pokemon-trading-card
php -S localhost:8000
```

Open `http://localhost:8000` in your browser.

### Default Admin Account

| Username | Password | Role |
|----------|----------|------|
| `admin`  | `admin`  | Admin |
| `demo`   | `demo1234` | User |

New users start with a balance of €2,000.

## Usage

### As a User
1. **Sign up** with a username, email, and strong password
2. **Browse** cards on the marketplace and filter by type
3. **Buy** cards (max 5 cards, must have sufficient balance)
4. **View** your collection on the User Details page
5. **Sell** cards back for 90% of their original price

### As Admin
1. **Log in** with admin credentials
2. **Add new cards** with name, stats, type, price, description, and image
3. Cards are validated server-side before being added to the catalog

## Tech Stack

| Category | Technologies |
|----------|-------------|
| Backend | PHP 7.4+ (vanilla, no framework) |
| Frontend | HTML5, CSS3, JavaScript |
| Data Storage | JSON files |
| Authentication | PHP Sessions, `password_hash()` / `password_verify()` |
| File Upload | PHP `move_uploaded_file()` with type/size validation |

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

**Isroilbek Jamolov** — [GitHub](https://github.com/Jamolov-Isroilbek)

---

<p align="center">
  <i>Built as a university web programming project at ELTE</i>
</p>
