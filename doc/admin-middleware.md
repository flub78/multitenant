# Admin middleware

Users have an admin field. By default the first registered user of the central or tenant application is admin. others registered users are not. The first admin user can then set or reset the user admin fields and create others admin.

A middleware exists to reserve some routes to admin users.

And the $user->isAdmin function can be used to protect blade templates sections. 