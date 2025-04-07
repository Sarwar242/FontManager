# Font Manager

A PHP Object-Oriented Project for managing and organizing fonts.

## Description

Font Manager is a simple yet powerful application to help designers and developers manage their font collections. Built with OOP principles in PHP, it provides an easy way to catalog, preview, and organize fonts.

## Features

- Font uploading and storage
- Font categorization and tagging
- Font preview capabilities
- Search and filter functionality
- User management

## Installation

```bash
# Clone the repository
git clone https://github.com/yourusername/FontManager.git

# Navigate to the project directory
cd FontManager

# Install dependencies
composer install
```

## Usage

```php
// Example code showing how to use the library
$fontManager = new FontManager\Core\Manager();
$fontManager->addFont('/path/to/font.ttf', 'Font Name', ['category' => 'sans-serif']);
$fonts = $fontManager->searchFonts(['category' => 'sans-serif']);
```

## Project Structure

```
FontManager/
├── src/
│   ├── Core/
│   ├── Models/
│   ├── Controllers/
│   └── Views/
├── public/
│   ├── assets/
│   ├── fonts/
│   └── index.php
├── tests/
├── vendor/
├── composer.json
└── README.md
```

## Requirements

- PHP 7.4 or higher
- Composer
- Web server (Apache/Nginx)

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.