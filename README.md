# Selling Dashboard API Documentation

This API was made to control the CRUD operations of the sales page in this point-of-sale project.

**** Response Schema: JSON OBJECT {"success": Boolean, "message_code": String, "body": Array} 

**** GET /sales  

* Fetches all items (Stocks) from the database
* Request arguments: none
* Returns: An object as per below: { 'success': boolean, 'items': array_of_objects, 'cost': float, 'price': float, 'available_quantity': integer }
* 404 will be returned if No item was found

**** GET /sales/list - Get all user transactions

* Fetches today user transactions by user_id
* Request Arguments: user_id: integer.
* Returns: An object as per below: { 'success': boolean, 'item_name': array_of_objects, 'quantity': integer, 'total': float }
* 404 will be returned if No transaction was found

**** GET '/sales/list/single' - Get one transaction 

* Fetches the selected transaction to edit from database
* Request Arguments: transaction_id: integer.
* Returns: An object as per below: { 'success': boolean, 'item_name': array_of_objects, 'quantity': integer, 'total': float }
* 404 will be returned if not found

**** GET /sales/list/topprices

* Fetches top five expensive items FOR using in Admin dashboard chart
* Request Arguments: none.
* Returns: An object as per below: { 'success': boolean, 'item_name': string, 'price': float }
* 404 will be returned if No item was found

**** POST /sales/create 

* Creates a new user transaction 
* Request Arguments: None.JSON data: {'item_id':integer, 'quantity':integer, 'total':float, 'current_user_id':integer}
* Returns: An object as per below: { 'success': boolean, 'items': array_of_objects, 'cost': float, 'price': float, 'available_quantity': integer }
* 422 will be returned if any param was not provided

**** PUT /sales/update

* Update selected transaction info on the database
* Request Arguments: None.JSON data: {'item_id':integer, 'item_name':string, 'quantity':integer, 'total':float, 'transaction_id':integer}
* Returns: An object as per below: { 'success': boolean, 'item_id': integer, 'item_name': string, 'total': float, 'quantity': integer }
* 422 will be returned if any param was not provided
* 404 will be returned if not found

**** DELETE /sales/delete

* Delete transaction on the database
* Request Arguments: id.
* 422 will be returned if id param was not provided
* 404 will be returned if not found
