

ALTER PROCEDURE [dbo].[JK_IN_loss](@wcid varchar(1),@cust_id varchar(50),@per_id varchar(50),@s_dt datetime,@loss_time numeric(20,8),@reason1 varchar(max),@reason2 varchar(max),@reason3 varchar(max),@reason4 varchar(max),@reason5 varchar(max),@result varchar(MAX) output)



WITH RECOMPILE
AS 
BEGIN 
--set @loss_time=@loss_time/60;
	declare @reason_PK decimal
	if(@cust_id=(select dbo.Odr_Customer.CustomerId from dbo.Odr_Customer where CustomerId=@cust_id ))
	begin
		
		if(@per_id=(select dbo.Ba_Person_Work.per_id from dbo.Ba_Person_Work where per_id=@per_id and per_work_wcid=@wcid))
			begin
				

				----------------------------------------------------(cust_id & per_id 資料正確)
				begin
					if(
							(
							select count(reason_id) from dbo.loss_time
							where 
						    dbo.loss_time.wcid=@wcid and
						    dbo.loss_time.cust_id=@cust_id and
						    dbo.loss_time.per_id=@per_id and
						    dbo.loss_time.s_dt=@s_dt and
						    dbo.loss_time.loss_time=@loss_time     
							)=0
							and 
							(
							@wcid is not null and @wcid !='' and len(@wcid)=1 and
							@cust_id is not null and @cust_id !='' and @cust_id=(select dbo.Odr_Customer.CustomerId from dbo.Odr_Customer where dbo.Odr_Customer.CustomerId=@cust_id group by dbo.Odr_Customer.CustomerId) and
							@per_id is not null and @per_id !='' and @per_id=(select dbo.Ba_Person_Work.per_id from dbo.Ba_Person_Work where dbo.Ba_Person_Work.per_id=@per_id group by dbo.Ba_Person_Work.per_id) and
							@s_dt is not null and @s_dt !='' and 
							@loss_time is not null and @loss_time >0
							)
					  )
	
			begin
				select @reason_PK=(case when (select count(*)from dbo.loss_time)>0 then (select max(isnull(dbo.loss_time.reason_id,0)) from dbo.loss_time )+1  else 0 end)

				INSERT INTO [dbo].[loss_time]
					   ( 
					    [reason_id]
					   ,[wcid]
					   ,[cust_id]
					   ,[per_id]
					   ,[s_dt]
					   ,[loss_time]
					   )
				 VALUES
					   (
						@reason_PK
					   ,@wcid
					   ,@cust_id
					   ,@per_id
					   ,@s_dt
					   ,@loss_time
					   )
				--select @result= @@ROWCOUNT				--@result=0 寫入成功	=NULL無寫入
				--if(@result>0)
					begin
						if((@reason1!='' and @reason1 is not null) and  (@reason2!='' and @reason2 is not null) and (@reason3!='' or @reason3 is not null) and (@reason4!='' or @reason4 is not null) and (@reason5!='' or @reason5 is not null))
						begin
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason1,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason2,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason3,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason4,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason5,@result out
						end
						if((@reason1!='' and @reason1 is not null) and  (@reason2!='' and @reason2 is not null) and (@reason3!='' or @reason3 is not null) and (@reason4!='' or @reason4 is not null) and (@reason5='' or @reason5 is null))
						begin
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason1,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason2,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason3,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason4,@result out
						end
						if((@reason1!='' and @reason1 is not null) and  (@reason2!='' and @reason2 is not null) and (@reason3!='' or @reason3 is not null) and (@reason4='' or @reason4 is null) and (@reason5='' or @reason5 is null))
						begin
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason1,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason2,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason3,@result out
						end
						else
						if((@reason1!='' and @reason1 is not null) and  (@reason2!='' and @reason2 is not null) and (@reason3='' or @reason3 is null) and (@reason4='' or @reason4 is null) and (@reason5='' or @reason5 is null))
						begin
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason1,@result out
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason2,@result out
						end
						if((@reason1!='' and @reason1 is not null) and  (@reason2='' or @reason2 is null) and (@reason3='' or @reason3 is null) and (@reason4='' or @reason4 is null) and (@reason5='' or @reason5 is null))
						begin
							exec [dbo].[JK_IN_loss_reason] @reason_PK,@reason1,@result out
						end
					end

		
		select @result= @@ROWCOUNT	
	
		end
		
		end
				----------------------------------------------------
			end
			else
			begin
				set @result='10'		--per_id資料不正確
			end
	end
	else
	begin
		set @result='20'				--cust_id資料不正確
	end
END
