# Week 13 - MeowBase with Paw Button (Like) and Cat-specific meow feed.
This week's iteration of MeowBase adds a "Paw" / like button for cats. In addition, we can now view the meows of a particular cat by visiting their page. To start, create a new database in CPanel and connect your Replit via Secrets / environment variables (see also /classes/App.php )

# Database Schema
A "Paw" table has been added to our Database. It is related (via Foreign Keys / Primary Keys) to the User and Meow tables. See the `/migrations/setup.sql` for an updated structure. You'll need to run `/setup` to get the structure initialized. (just append `/setup` to your public replit URL).

# Performance and Caching
Previously the Model class would query on every page load to discover the structure and relationships in the database. Due to settings on Phoenix, These queries were quite laggy! To speed things up, information about the structure and relationships is now cached to a JSON file: `/cache/schema.json`. The initial caching takes a second, so you'll notice that the `/setup` takes a bit longer than before. Subsequent page loads, however, run more quickly. 

# Meows and Paws
Take a look at the updated `/partials/meows.php` where a Paw button has been added. The code has been refactored to be more DRY (i.e. don't repeat yourself). Some logic has been added to ensure an optimal user experience.

# Meows by a particular Cat
The meow feed is now included in two places: first on the dashboard as before, but now additionally on the page of each particular cat. Notice that `/views/cat.php` and `/views/dashboard.php` now both include `/partials/meows.php`.

# A Logging mechanism. 
It's helpful to do some basic profiling, to test how long things take. In connection with this look for the log() and logDump() functions in `/classes/App.php`. 