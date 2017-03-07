# **Forum**
#### Jason Brown, Matt Kelley, Xia Amendolara, Michaela Davis 3/6/2017

&nbsp;
## Description
A forum where a user may post create a profile with bio, post content, comment on forum content, rank other users, upload photos, and search content through hashtags.

&nbsp;
## Database Setup
* Start MAMP.
* Make sure MAMP is running in main project directory.
* - From Terminal -
* /Applications/MAMP/Library/bin/mysql --host=localhost -uroot -proot
* CREATE DATABASE forum;
* USE forum;
* CREATE TABLE users (username varchar (255), password varchar (255), user_photo varchar (255), rank varchar (255), user_score INT, bio text, location_city varchar (255), location_state varchar (255), location_country varchar (255), creation_date DATETIME, user_id serial primary key);
* CREATE TABLE comments (user_id INT, comment varchar (255), post_time DATETIME, score INT, parent_id INT, init_comment_id INT, thread_id INT, comment_id serial primary key);
* CREATE TABLE categories (category varchar (255), category_id serial primary key);
* CREATE TABLE threads (post text, category_id INT, thread_id serial primary key, user_id INT);
* CREATE TABLE comments_tags (ct_id serial primary key, comment_id INT, tag_id INT);
* create table threads_tags (tt_id serial primary key, tag_id INT, thread_id INT);
* create table tags (tag_id INT, tag varchar (255));

* From http://localhost:8888/MAMP/index.php?page=phpmyadmin&language=English select forum database.  Go to operations, copy database with structure only named forum_test.

&nbsp;
## Specifications

| Behavior | Input 1 | Output |
|--------|-------|------|
| User enters username | "Mike_B"| "Mike_B"|
| User enters password| "qwerty" | "qwerty" |
| User clicks on forum coding category. | 'Coding'| 'Coding' category pg. loads.|
| User clicks on Javascript thread. | 'Javascript'| 'Javascript' thread pg. loads.|
| User posts to Javascript thread. |"written stuff" | "written stuff" |
| User uploads a photo. | Href link to photo source. | Image displays on user post. |
| User searches for Drupal in search bar. | '#Drupal' | 'Drupal' thread pg. loads |
| User comments on forum post in a category. | "non-troll comment" | "non-troll comment" |
| User comments on an existing comment. | "Hey great comment!" | "Hey great comment!" |
| User ranks another user's comment. | 'Rank-up'| Said user's rank get's a +1 |
| User gets pushed to the top of the page. | A user gets many +1 rankings | User profile listed above others. |



&nbsp;
## Setup/Installation Requirements
##### _To view and use this application:_
* You will need the dependency manager Composer installed on your computer to use this application. Go to [getcomposer.org] (https://getcomposer.org/) to download Composer for free.
* Go to my [Github repository] (https://github.com/mkelley2/forum_project)
* Download the zip file via the green button
* Unzip the file and open the **_forum_project-master_** folder
* Open Terminal, navigate to **_forum_project-master_** project folder, type **_composer install_** and hit enter
* Navigate Terminal to the **_forum_project-master/web_** folder and set up a server by typing **_php -S localhost:8000_**
* Type **_localhost:8000_** into your web browser
* The application will load and be ready to use!

&nbsp;
## Known Bugs
* No known bugs

&nbsp;
## Technologies Used
* PHP
* Silex
* SQL
* Apache
* Twig
* PHPUnit
* Composer
* Bootstrap
* CSS
* HTML



Copyright (c) 2017 Jason Brown, Matt Kelley, Xia Amendolara, Michaela Davis

This software is licensed under the GPL license
