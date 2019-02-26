<div class="row">
    <div class="box col-md-12">
            <div class="box-inner">
                <div class="box-header well" data-original-title="">
                    <h2><?php echo $page_name;?></h2>

                    <div class="box-icon">
                        <a href="#" class="btn btn-setting btn-round btn-default"><i class="glyphicon glyphicon-cog"></i></a>
                        <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
                        <a href="#" class="btn btn-close btn-round btn-default"><i class="glyphicon glyphicon-remove"></i></a>
                    </div>
                </div>
                <div class="box-content">
					<?php if($manage_group) { ?>
					<form action="<?php echo $index_base_url?>review_do_upload" method="POST" enctype="multipart/form-data">
						<input type="file" name="files[]" multiple/ class='btn btn-inverse btn-default btn-sm '>
						
						<h5>制定人 : <input type="text" name='developers'></h5>
					
						<h5>目前僅提供 pdf 檔案格式 上傳</h5>
						<br>
						
						<input type="hidden" name='table' value ='<?php echo $method_name;?>'>						
						<div class="mc_embed_signup">
							<input type="submit" class='button' value ='上傳'>
						</div>
						<br>
						<h3><?php if(isset($_GET['msg_str'])){echo "<font color='red'>".$_GET['msg_str']."</font>";}?></h3>
					</form>
					<?php }?>
					<form id ='query_form'  method ='GET' action ='<?php echo $index_base_url; echo $method_name;?>'>
						<ul class='pagination pagination-centered'>
							<?php echo $show_last_page;?>
							<li class='active'><a href='#'>第<?php echo $now_page+1;?>頁，共<?php echo $page_total;?>頁</a></li>				
							<?php echo $show_next_page;?>
							
								
											
						</ul>
						<ul class='pagination pagination-centered'>
							<select class='form-control' name='users' id='users' ><?php print_r($all_users);?>
																				
							<input class='form-control' type='text' id='datepicker' name='datepicker' readonly="readonly" placeholder='點此選擇查詢日期' class='hasDatepicker'>
						</ul>
						<ul class='pagination pagination-centered'>								
							<input class='btn btn-primary btn-sm' type='button' value='查詢' onclick='JavaScript:showPage()'>								
							<input class='btn btn-primary btn-sm' type='button' value='清空' onclick='clear_value()'>								
						</ul>
					</form>			
                    <table class="table">
                        <thead>
                        <tr>
                            <th>序號</th>
                            <th>工作卡名稱</th>			
							<th>下載</th>
                            <th>制定時間</th>
							<th>修改時間</th>
							<th>檔案狀態</th>
							<th>版本</th>                            
							<th>制定人</th>
							<th>修訂人</th>
							<?php if($manage_group){?>
							<th>刪除</th>
							<?php }?>	
                        </tr>
                        </thead>
                        <tbody>
							<?php foreach($files_data as $data){ ?>
							
								<tr>
									<td><?php echo $data['seq'];?></td>
									<td class="center"><?php echo $data['file_name'];?></td>									
									<td class="center"><button class="btn btn-primary btn-sm glyphicon glyphicon-download-alt" onclick="window.open('<?php echo $base_url; echo $download_link; echo $data['file_name'];?>')"></button></td>							
									<td class="center"><?php echo $data['upload_time'];?></td>
									<?php if($data['update_time'] == '0000-00-00 00:00:00'){
										$data['update_time'] ='';
									}	
									?>
									<td class="center"><?php echo $data['update_time'];?></td>
									<td class="center">
										<?php if($data['ver'] > 1){?>
											<span class="label-default label">修改成功</span>
										<?php }else{?>
											<span class="label-success label label-default">上傳成功</span>
										<?php }?>
									</td>
									<td><?php echo $data['ver'];?></td>
									<td class="center"><?php echo $data['author'];?></td>
									<td class="center"><?php echo $data['editor'];?></td>
									<?php if($manage_group){?>
									<td class="center"><button class="btn btn-danger btn-sm glyphicon glyphicon glyphicon-trash" onclick="self.location.href='<?php echo $index_base_url;?>del_files?page=<?php echo $method_name;?>&del_name=<?php echo $data['file_name'];?>'"></button></td>
									<?php }?>	
								</tr> 								
							<?php }?>
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
</div>	
</div>
</div>
</div>