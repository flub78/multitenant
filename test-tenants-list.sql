use multi_test;
select tenant_id,domain from tenants, domains where tenant_id=tenants.id;