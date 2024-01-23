# Real-time Stock Price Aggregator

## Project Description

This is an backend system for real-time stock data aggregation from Alpha Vantage API. Features include API integration, caching, optimized database storage, data processing, scheduled tasks, and comprehensive automated testing. Easy setup with Docker support. Full documentation provides clear instructions for running the application.

-   The application uses Laravel, a PHP web application framework, for its simplicity, scalability, and built-in features.
-   The [Alpha Vantage API](https://www.alphavantage.co/documentation/) is used to fetch real-time stock data.
-   Stock and StockPrice models are used to store stock-related data in the database.
-   The application includes a console command (`FetchStockData`) to fetch and store stock data from Alpha Vantage.

# Table of Contents

- [Real-time Stock Price Aggregator](#real-time-stock-price-aggregator)
  - [Project Description](#project-description)
- [Table of Contents](#table-of-contents)
- [Project Setup and Running Instructions](#project-setup-and-running-instructions)
  - [Manual Setup](#manual-setup)
    - [Prerequisites](#prerequisites)
    - [Setup Steps](#setup-steps)
  - [Dockerized Version](#dockerized-version)
    - [Prerequisites](#prerequisites-1)
    - [Setup Steps](#setup-steps-1)
  - [Running Scheduled Commands](#running-scheduled-commands)

# Project Setup and Running Instructions

## Manual Setup

### Prerequisites

-   PHP (>= 8.1)
-   Composer
-   MySQL or any other database of your choice
-   Laravel CLI

### Setup Steps

1. **Clone the Repository:**

    ```bash
    git clone https://github.com/AleyaSiddika/stock-price-aggregator.git
    ```

2. **Install Dependencies:**

    ```bash
    cd stock-price-aggregator
    composer install
    ```

3. **Create Environment File:**

    ```bash
    cp .env.example .env
    ```

    Edit the `.env` file and set your database and other configuration settings.

4. **Generate Application Key:**

    ```bash
    php artisan key:generate
    ```

5. **Run Migrations:**

    ```bash
    php artisan migrate
    ```

6. **Serve the Application:**

    ```bash
    php artisan serve
    ```

    The application will be accessible at [http://localhost:8000](http://localhost:8000).

## Dockerized Version

### Prerequisites

-   Docker
-   Docker Compose

### Setup Steps

1. **Clone the Repository:**

    ```bash
    git clone https://github.com/AleyaSiddika/stock-price-aggregator.git
    ```

2. **Build and Run Docker Containers:**

    ```bash
    cd stock-price-aggregator
    docker-compose up --build
    ```

3. **Run Migrations:**

    Open a new terminal window, navigate to the project root, and run:

    ```bash
    docker-compose exec app php artisan migrate
    ```

4. **Access the Application:**

    The application will be accessible at [http://localhost:8000](http://localhost:8000).

## Running Scheduled Commands

For the manual setup, run the following command in the project root:

```bash
php artisan schedule:run
```

For the Dockerized version, use the following command:

```bash
docker-compose exec app php artisan schedule:run
```

This will execute the scheduled commands defined in the Laravel scheduler.

Now, you have successfully set up and run the Laravel application manually and in a Dockerized environment.
