
ALTER PROCEDURE [dbo].[check_userdata_1](@aid nchar(20))
AS
BEGIN	
	SET NOCOUNT ON;

	select a.name,b.pid,b.enable ,c.name
	from [dbo].[user_data] a 
		left join [dbo].[user_permissions] b on a.account_id = b.account_id
		left join [dbo].[user_permissions_list] c on c.pid=b.pid 
	where a.account_id = @aid



END
         
