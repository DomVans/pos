POS assignment using Laravel and Filament submitted by Dominic Vansangzuala

Mizoram University B.Tech I.T 8th Semester

Steps on how to run the POS system
1. Clone the Repository
"git clone https://github.com/DomVans/pos.git"

2. Install Dependencies
"composer install"

3. Copy the .env.example file to create the .env file:
"copy .env.example .env"

4. Open the .env file and configure database settings like this
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pos
DB_USERNAME=root
DB_PASSWORD=

6. Run the database migrations
"php artisan migrate"

7. Create the admin using filament
"php artisan make:filament-user"

8. Run the development server
"php artisan serve"

9. Log in using the credential created in step 7

How to use the System?

Users - We can create other user admins and edit or delete them as needed.

Inventory Management:

Categories - We can create the category the product is going to be in. For example Foods, Drinks, TV, Smartphones, etc.

Products - We can create the product here with all the necessary information like product name, barcode, etc.

Stocks - We can create how many stocks are here, the stock name and the stock number.

Products Stocks - Here we can assign the price of the product to different stocks, for example, we have a product, we can assign it stock 1 and enter the price and the quantity in stock 1, the same product can also be assigned to stock 2 and have a different price and quantity.

Sales Management:

Sales - We can sale the items here, we can sell it in batch by selecting or searching the product name or the barcode along with its stock number in the sale items using the "add to sale items" button, this will create another form where we can sell another product, the final amount ater the discount will be displayed in the sale details.

To be added later:

Decreasing the quantity of products in the stock everytime an item is sold.
