select:

select cardid from testemployee where cardid != '' group by cardid having count(cardid)>1

delete:

delete from testemployee where  cardid in (select a.cardid from  (select cardid from testemployee where cardid != '' group by cardid having count(cardid)>1) a)
