<h5>Installation</h5>
<p>Copy env file: cp .env.example .env</p>
<br />
<p>Setup your db info</p>
<p>DB_CONNECTION=mysql</p>
<p>DB_HOST=127.0.0.1</p>
<p>DB_PORT=3306</p>
<p>DB_DATABASE={{db}}</p>
<p>DB_USERNAME={{db_user}}</p>
<p>DB_PASSWORD={{db_password}}</p>
<br/>
<p>Generate app key</p>
<p>php artisan key:generate</p>

<p>Mirgate DB</p>
<p>php artisan db:migrate</p>

<p>Run Seeder</p>
<p>php artisan db:seed</p>
<p> You can change the admin user and password in database/seeders/DatabaseSeeder.php</p>