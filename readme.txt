************************************************************
--- Starting - 30/05/2025 ---
--- Log all the changes on every commit ---
Heading Format - Date [dd/mm/yyyy]
- {List all changes that is done in the commit}
************************************************************

Date: 31/05/2025
- Admin Panel - Orders page.
  - Worked on the UI.
  - Orders are displayed in a table.
- Admin Panel - Users page.
  - Updated UI to display the users in a table layout.
  - Added more columns like, Phone, ID, Role etc.

Date: 06/06/2025
- User Profile - Profile Information
  - Created new Routes, Views, and controller
  - Updated UI to display basic Information.

Date: 20/06/2025
- Stripe Payment
  - Added scripts to handle new payment options.
    - Amazon Pay
    - Cash App
- Changed currency from rupees to dollars. (compatible for most payment options)
- Orders table - now the payment method type will be saved instead hardcoded "Stripe" value.
- And may be something, cant't remember.

Date: 21/06/2025
- Admin Login
  - Redirect the user back to homepage if tried to login with normal user.
- User interaction
  - Added popup to display messages to user.
  - Removed the alert where user is alerted about the product added to cart.
  - Replaced it with the new popup modal.
- Homepage
  - Updated the layout of the product grid.(improvements)

Date: 22/06/2025
- Admin Panel
  - Order table - Displayed only basic data about the order.
  - Added view order button.
  - Added new page to show the complete order details after clicked on the view orders button.
