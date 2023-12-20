# Overview
Laravel multiple roles with permission management system. SuperAdmin can able to manage other roles permissions. So, other roles can able access any module create, read, update and delete functionality on basis of permission given. using <a href="https://spatie.be/docs/laravel-permission/v5/introduction" target="_blank">Spatie/laravel-permission</a> package.

Server Requirements
=====================================
<ul>
  <li><a href="https://www.php.net/" target="_blank">PHP Version</a> => 7.4 or higher</li>
  <li><a href="https://laravel.com/docs/master" target="_blank">Laravel Version</a> => 8 or higher</li>
  <li><a href="https://www.mysql.com/" target="_blank">MySQL Version</a> => 5.7 or higher</li>
</ul>

# Project Requirement
<ul>
<li>Basic Laravel Auth: ability to log in with below Roles</li>
<ol>
<li>SuperAdmin (Can manage Products, Orders, Administrators, Members)</li>
<li>Administrator ( Can manage Products, Orders, Members)</li>
<li>Member (Can create multiple Order)</li>
</ol>
<li>Use database seeders to create SuperAdmin with email "superadmin@yopmail.com" and password "superadmin@123"</li>
<li>Use database seeders to create Administrator with email "admin@yopmail.com" and password "admin@123"</li>
<li>Use basic Laravel resource controllers with default methods - index, create, store, show etc.</li>
<li><b>Product Module CRUD fields</b></li>
<li>Product Name : [ Validation Rules : Accept only strings and space, Required ]</li>
<li>Product Image : [ Validation Rules : Accept only png and jpg, Maximum 5 mb size, Required ]</li>
<li>Product Description : </li>
<li>Product Price : [ Validation Rules : Accept only double numbers, Required ]</li>
<li>Product Stock/Qty : [ Validation Rules : Accept only numbers, Maximum lenght 9, Required ]</li>
<li><b>Order Module CRUD fields</b></li>
<li>Order Id : </li>
<li>Multiple Product Select : </li>
<li>Total Amount: [Auto Fetch price base on products selection]</li>
</ul>

# Laravel / PHP Coding Standards
<ol>
 <li>Use Laravel's migration scripts for database schema and tables related to further all operations</li>
 <li>Use Laravel's factory and seeders for sample dummy data creations where required</li>
 <li>Use Laravel's validation using Request classes where required</li>
 <li>Use Laravel's Eloquent and Relationships in models where required</li>
 <li>Use camel case for function name and variable name. E.g getProductDetails() , $categoryDetails </li>
 <li>Comment on the above function with short details of that function use or purpose of function creation. </li>
 <li>Reuse the common codes using some helper class functions etc</li>
 <li>Remove unnecessary code and debug points that are not needed</li>
 <li>Avoid unnecessary loops if not required</li>
 <li>Avoid unnecessary variable creations</li>
 <li>Use PHP design patterns where required <a href="https://refactoring.guru/design-patterns/php" target="_blank">Design Patterns</a></li>    <li>Normalize database tables where required <a href="https://www.guru99.com/database-normalization.html" target="_blank">Database Normalization</a></li>
 <li>Optimize database tables</li>
 <li>Avoid MySQL joins queries if not required</li>
 <li>Avoid MySQL sub queries if it does not require</li>
 <li>For more information, please read the document <a href="https://drive.google.com/drive/folders/1_nxEPw01QnVkVQfZ2WtXyeX7NcQ6ENdh" target='_blank'>Code Standard</a>
</ol>

# References
<ol>
 <li>https://www.itsolutionstuff.com/post/laravel-10-user-roles-and-permissions-tutorialexample.html</li> 
<ol> 

# Search Key
<ol>
  <li>KEY : MULTIPERMISSION</li>
</ol>