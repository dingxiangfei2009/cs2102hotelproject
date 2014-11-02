-- post schema setup
grant select on Hotel to userapp;
grant select on Room to userapp;
grant select, update, insert, delete on Customer to userapp;
grant select, update, insert, delete on MakeBooking to userapp;
grant select, update, insert, delete on Contains to userapp;
