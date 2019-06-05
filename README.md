# UVA Coupons

<img width="100%" alt="web_application" src="https://user-images.githubusercontent.com/42804316/58922628-bef9ad00-8709-11e9-903d-197a9a76af10.png">

This project aims at developping a website called UVA Coupons where people can not only browse coupons from online sources, but also post and search local coupons in Charlottesville, VA. To achieve this goal, a backend databased on MySQL was built and a frontend application using VUE.js was created. 

##  Idea Development and User Requirement

We envisioned the UVA Coupon application as a website where users can register an account and then search both online and local coupons. The two main pages on the website would be Coupons and Forum. The Coupons page would display the information for online coupons such as discount description, product type and web link. The Forum page would show local coupon information uploaded by registered users. Since we would like to encourage users to upload more coupon information, we added a points system for the website. For instance, each user will have 0 points at registration and then they can earn points by clicking online coupons or creating new posts about local promotions. 

The targeted users for our application are UVA students and local residents in Charlottesville where many activities happening in the area. Information about some local promotions are not easily accessed online and sometimes can only be obtained by word of mouth. We believe our application will help the users to save money and time by providing a channel that gathers and distributes the coupon information. Moreover, local stores and businesses can also benefit from this application due to increased customer traffic.

## Database Design

To implement our idea, we started with the database design. All the data is stored in a database created on MySQL. We created six tables to model the proposed application. Three tables are entities: User, Online_Coupon and Local_Coupon. The other three are relationships between the entities:  Clicks_1, Clicks_2, and Posts. Figure 1 shows the entity relationship diagram (ERD) for our database and the primary key for each entity is underlined. The User table stores information such as a unique ID, name and the balance of points for each registered user. The Online_Coupon and Local_Coupon tables contain detailed information for the coupons. Some important information are the unique coupon ID, discount description, effective date, expiration date, and a link for displayed photos. Both Clicks_1 and Clicks_2 are many-to-many relationships because a coupon can be clicked by multiple users and a user can click multiple coupons. Since the click action happen on a specific time, the two clicks tables also have Date as an attribute. Posts is a many-to-one relationship with one on the User side since a local coupon can only be posted by one single user. It also has total participation as all local coupons have to be posted by some user. Table 1  shows the schema statements for these tables. 

<img width="639" alt="ERD" src="https://user-images.githubusercontent.com/42804316/58917529-ec882b80-86f4-11e9-8337-cf0f9bdb70d7.png"><img width="643" alt="ERD_statement" src="https://user-images.githubusercontent.com/42804316/58917551-fdd13800-86f4-11e9-97f5-c1b7d5425ad7.png">

## Data

Regarding data source for the tables, we initially wanted to scrape coupon information from existing website for online coupons and generate synthetic data for local coupons. We utilized the beautifulsoup library in Python to scrape hot deals from Dealmoon. The code for scraping can be found below. However, with the concern about copyright and inconsistency between the information scraped and the designed schema, we decided to use synthetic data for both types of coupons in our database. 

<img width="554" alt="webscrape_code" src="https://user-images.githubusercontent.com/42804316/58922388-9d4bf600-8708-11e9-9e85-3630e55b8810.png"><img width="502" alt="webscrape_code_2" src="https://user-images.githubusercontent.com/42804316/58923028-cc179b80-870b-11e9-9c4a-e1bd43c51d8e.png">

A reference for scaping code: [link](https://www.dataquest.io/blog/web-scraping-tutorial-python/)

## SQL Queries

A plenty of SQL queries were developed for six tables to fulfill the corresponding tasks as add/insert, delete, update and retrieve information. The detailed queries are accessible from the **SQL_queries.docx** file. The following table lists the possible transactions for each table.

<img width="646" alt="list_possible_transactions" src="https://user-images.githubusercontent.com/42804316/58923158-6d065680-870c-11e9-8737-a3932a6bf4e4.png">

## Backend Design

For the backend finished by my teammates, seven php files were develped to implement the following five functions: 1. Get information from the webpage; 2. Check whether these information is valid; 3. Use these information to make a sql command; 4. Connect to the database and execute these SQL commands; 5. Return the result to the webpage. Among these php files, the basic one is called dbconnection.php, which is used to connect to our database on mysql.cs.virginia.edu:3306. The flow chart below shows the logic of the backend design.

<img width="414" alt="backend_design" src="https://user-images.githubusercontent.com/42804316/58923070-03864800-870c-11e9-91ec-f71bf586d8b3.png">

## Demo of Web

Here is the demo for the web application:

![ezgif com-video-to-gif](https://user-images.githubusercontent.com/42804316/58924728-046ea800-8713-11e9-9f2e-f8355cb85a54.gif)
