insert into cca.los_menuitems(id,provider_id,item_name,description,price,active,created_at,updated_at)
select id,provider_id,concat(substr(item_name,1,47),id),item_name,price,active,created_at,updated_at
from homestead.los_menuitems;


insert into cca.los_nolunchexceptions(id,exception_date,grade_id,teacher_id,reason,description,created_at,updated_at)
select id,exception_date,grade_id,teacher_id,reason,description,created_at,updated_at
from homestead.los_nolunchexceptions;


insert into cca.los_accounts(id,account_name,email,password,active,allow_new_orders,created_at,updated_at)
select id,account_name,email,password, 1,1,created_at,updated_at
from homestead.los_accounts
where id not in (1,2,4);

insert into cca.los_users(id,account_id,first_name,last_name,allowed_to_order,teacher_id,grade_id,user_type,created_at,updated_at)
select id,account_id,first_name,last_name,allowed_to_order,teacher_id,grade_id,user_type,created_at,updated_at
from homestead.los_users
where account_id not in (1,2,4);

insert into cca.los_payments(id,account_id,pay_method,credit_amt,fee,credit_date,credit_desc,created_at,updated_at)
select id,account_id,pay_method,credit_amt,fee,credit_date,credit_desc,created_at,updated_at
from homestead.los_payments;

insert into cca.los_lunchdates(id,provider_id,provide_date,orders_placed,additional_text,extended_care_text,created_at,updated_at)
select id,provider_id,provide_date,orders_placed,additional_text,extended_care_text,created_at,updated_at
from homestead.los_lunchdates;

insert into cca.los_lunchdate_menuitems(id,lunchdate_id,menuitem_id,created_at,updated_at)
select id,lunchdate_id,menuitem_id,created_at,updated_at
from homestead.los_lunchdate_menuitems;

insert into cca.los_orders(id,account_id,user_id,lunchdate_id,order_date,short_desc,total_price,status_code,entered_by_account_id,created_at,updated_at)
select id,account_id,user_id,lunchdate_id,order_date,short_desc,total_price,status_code,entered_by_account_id,created_at,updated_at
from homestead.los_orders
where deleted_at is null;

insert into cca.los_orderdetails(id,account_id,order_id,menuitem_id,qty,price,created_at,updated_at)
select id,account_id,order_id,menuitem_id,qty,price,created_at,updated_at
from homestead.los_orderdetails
where deleted_at is null;

update los_orderdetails od, los_menuitems m
set od.provider_id=m.provider_id
where od.menuitem_id = m.id;

insert into assigned_roles(role_id, entity_id, entity_type)
select 2, id, 'App\Models\Account' from los_accounts a
where a.id not in (1,2,4);

UPDATE los_accounts a SET confirmed_credits=(SELECT coalesce(SUM(credit_amt),0) FROM los_payments p WHERE a.id = p.account_id);
UPDATE los_accounts a SET fees=(SELECT coalesce(SUM(fee),0) FROM los_payments p WHERE a.id=p.account_id);
UPDATE los_accounts a SET total_debits=(SELECT coalesce(SUM(total_price),0) FROM los_orders o WHERE a.id = o.account_id AND o.status_code < 2);
UPDATE los_accounts a SET confirmed_debits=(SELECT coalesce(SUM(total_price),0) FROM los_orders o WHERE a.id = o.account_id AND o.status_code = 1);
UPDATE los_accounts a SET total_orders=(SELECT coalesce(SUM(qty),0) FROM los_orderdetails o WHERE a.id = o.account_id);

update cca.los_menuitems a, homestead.los_menuitems_new b
set a.item_name=b.item_name
where a.id = b.id;


