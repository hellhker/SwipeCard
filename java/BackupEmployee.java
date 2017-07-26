package com.backup.employee;


import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import org.apache.commons.lang.ArrayUtils;

public class BackupEmployee {

	static String sql1 = null;
	static String sql2 = null;
	static String sql3 = null;
	static String sql4 = null;
	static String sql_countNew = null;
	static String sql_countOld = null;
	
	static DBHelper db1 = null;
	static DBHelper db2 = null;
	static DBHelper db3 = null;
	static DBHelper db4 = null;
	static DBHelper dbNew = null;
	static DBHelper dbOld = null;
	
	
	static ResultSet ret = null;
	static int res;
	 
	

	public static boolean contains(String[] arr, String targetValue){
		 return ArrayUtils.contains(arr,targetValue);
	}
	
	@SuppressWarnings("null")
	public static void main(String[] args) {
		System.out.println("test");
//		sql = "select * from view_emp rand() limit 100";// SQL语句
//		sql1 = "select * from view_rybz_cabg";// SQL语句
		
		sql1 = "select * from testemployee1 order by id";// SQL语句
		
		sql2 = "select * from testemployee";//
		
		
//		sql = "select * from view_emp where iccardid = '0313240578'";
		//sql2 = "INSERT INTO `testemployee`(`ID`, `Name`, `depid`, `depname`,`cardid`,`direct`,`costID` ) VALUES (?,?,?,?,?,?,?)";
//		sql2 = "INSERT INTO `testemployee`(`ID`, `Name`, `depid`, `depname`, `cardid`) VALUES ('133028','徐文勇','7933','策略-資源管理-數據-系統整合課','0313240578')";
		//sql3 = "DELETE FROM `testemployee`";
		//sql4 = "UPDATE `testemployee` SET `Permission` = '1' WHERE ID IN ('01394','208648','40908','68951','CY439','KQ933','KV447','N6193','R0405','R5632','G0956','403935','U2782','WL930','R1004','286943','T9220','D3702','K7804','N5400','58788','FE440','G8611','N6398','N6315','N5289','N3447','A5798','G6659','156339','03236','AW050','04664','F0025','K4230','F0660','KQ728','256176','DC705','U1939','K7380','194236','175418','565535','368364','N5397','426017')";
		Connection conn = DBHelper.getConnection();
		System.out.println("A");
		PreparedStatement psNew = null;//最新资料
		ResultSet rsNew = null;
		
		PreparedStatement psOld = null;//旧的资料
		ResultSet rsOld = null;
		
		ResultSet countNew = null;
		ResultSet countOld = null;
		
		System.out.println("B");
		sql_countNew = "select count(*) from testemployee1 order by id";
		sql_countOld = "select count(*) from testemployee order by id";
		db1 = new DBHelper(sql1);// 创建DBHelper对象 |SelectCRUD Create result Delete
		db2 = new DBHelper(sql2);// 创建DBHelper对象 |Create
		//db3 = new DBHelper(sql3);
		//db4 = new DBHelper(sql4);
		dbNew = new DBHelper(sql_countNew);
		dbOld = new DBHelper(sql_countOld);
		/**
		 * 注意事项：PreparedStatement来自于java.sql.PreparedStatemet
		 */

		// 4.执行SQL语句
		System.out.println("123");
//		String[] uids = null;
//		String[] unames = null;
//		String[] udepnames = null;
//		String[] udepids = null;
//		String[] ucardids = null;
//		String[] udirects = null;
//		String[] ucostids = null;
//		
//		String[] uids2 = null;
//		String[] unames2 = null;
//		String[] udepnames2 = null;
//		String[] udepids2 = null;
//		String[] ucardids2 = null;
//		String[] udirects2 = null;
//		String[] ucostids2 = null;
		
		
		int i=0;
		int j = 0;
		try {
			//db3.sta.executeUpdate();
//			psNew = conn.prepareStatement(sql1);
//			rsNew = psNew.executeQuery();// 执行查询
			
			rsNew = db1.sta.executeQuery();
			rsOld = db2.sta.executeQuery();
			
			countNew =  dbNew.sta.executeQuery();
			countOld =  dbOld.sta.executeQuery();
			int newCount = 0;
			int oldCount = 0;
			while (countNew.next()) {
				 newCount = countNew.getInt(1);
				
				
			}
			
			while (countOld.next()) {
				 oldCount = countOld.getInt(1);
				
			}
			
//			rsNew.last();
//			rsOld.last();
//			int newCount = rsNew.getRow(); //获得ResultSet的总行数
//			int oldCount = rsOld.getRow(); //获得ResultSet的总行数
//			int n =0;
//			rsNew.absolute(n);
//			rsOld.absolute(n);
//			rsNew.first();
//			rsOld.first();
			String[] uids = new String[newCount];
			String[] unames =  new String[newCount];
			String[] udepnames =  new String[newCount];
			String[] udepids =  new String[newCount];
			String[] ucardids =  new String[newCount];
			String[] udirects =  new String[newCount];
			String[] ucostids =  new String[newCount];
			
			String[] uids2 = new String[oldCount];
			String[] unames2 = new String[oldCount];
			String[] udepnames2 = new String[oldCount];
			String[] udepids2 = new String[oldCount];
			String[] ucardids2 = new String[oldCount];
			String[] udirects2 = new String[oldCount];
			String[] ucostids2 = new String[oldCount];
			
			
			while (rsNew.next()) {// 判断是否还有下一个数据
				String uid = rsNew.getString(1);
				String uname = rsNew.getString(2);
				String udepname = rsNew.getString(3);
				String udepid = rsNew.getString(4);
				String ucardid = rsNew.getString(6);
				String udirect = rsNew.getString(5);
				String ucostid = rsNew.getString(7);
				uids[i] = uid;
				unames[i] = uname;
				udepnames[i] = udepname;
				udepids[i] = udepid;
				ucardids[i] = ucardid;
				udirects[i] = udirect;
				ucostids[i] = ucostid;
				i++;
				
				
//				db2.sta.setString(1, uid);
//				db2.sta.setString(2, uname);
//				db2.sta.setString(3, udepname);
//				db2.sta.setString(4, udepid);
//				db2.sta.setString(5, ucardid);
//				db2.sta.setString(6, udirect);
//				db2.sta.setString(7, ucostid);
//				res = db2.sta.executeUpdate();// 插入數據
				System.out.println(uid +"\t"+ uname + "\t" + udepname + "\t"
					+ udepid + "\t" + ucardid);
			}
//			System.out.println(i);
			j = 0;
			
			while(rsOld.next()){
				String uid = rsOld.getString(1);
				String uname = rsOld.getString(2);
				String udepname = rsOld.getString(3);
				String udepid = rsOld.getString(4);
				String ucardid = rsOld.getString(6);
				String udirect = rsOld.getString(5);
				String ucostid = rsOld.getString(7);
				
				uids2[j] = uid;
				unames2[j] = uname;
				udepnames2[j] = udepname;
				udepids2[j] = udepid;
				ucardids2[j] = ucardid;
				udirects2[j] = udirect;
				ucostids2[j] = ucostid;
				j++;
			}
			int x=0;
			int y =0;
			for(int k=0;k<i;k++){
				
				if(contains(uids2,uids[k])==true){
					x++;
				}else{
					y++;
				}
				
			}
			System.out.println(x);
			System.out.println(y);
			
			
			
			
			//db4.sta.executeUpdate();
			// ret = db1.pst.executeQuery();// 执行语句，得到结果集
//			while (ret.next()) {
//				String uid = ret.getString(1);
//				String ufname = ret.getString(2);
//				db2.sta.setString(1, uid);
//				db2.sta.setString(2, ufname);
//				// res = db2.sta.executeUpdate();
//				// String ulname = ret.getString(3);
//				// String udate = ret.getString(4);
//				// System.out.println(uid + "\t" + ufname + "\t" + ulname + "\t"
//				// + udate);
//				// System.out.println(uid + "\t" + ufname);
//			}// 显示数据
//				// res = db2.sta.executeUpdate();
//			ret.close();
			// res.close();
			//db4.close();
			db1.close();// 关闭连接
			db2.close();
			//db3.close();
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}
	


}
