<?php
/**
 * The Table Data Gateway is used mainly for a single table or view.
 *
 It contains all the selects, inserts, updates, and deletes.
 * So Customer is a table or a view in your case. So, one instance of a table data
 * gateway object handles all the rows in the table. Usually this is related to
 * one object per database table.

Data Mapper is more independent of any domain logic and is less coupled
 * It is merely a intermediary layer to transfer the data between objects
 * and a database while keeping them independent of each other and the mapper itself.

So, typically in a mapper, you see methods like insert, update, delete
 * and in table data gateway you will find getcustomerbyId, getcustomerbyName, etc.
 *
 */

namespace App\Main;


class User
{

}