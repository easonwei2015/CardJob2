<script src="<?php echo $base_url;?>assets/js/highcharts.js"></script>
<script src="<?php echo $base_url;?>assets/js/modules/data.js"></script>
<script src="<?php echo $base_url;?>assets/js/modules/exporting.js"></script>
<style>
[class*='span'] {
	margin-left: 30px;
	float: left;
	min-height: 1px;
}

#DIV1{
width:200px;　
line-height:50px;　
padding:20px;　
border:2px blue solid;　//靠右外距，參閱：CSS margin 邊界使用介紹範例教學。
float:left;
}
#DIV2{
width:200px;
line-height:50px;
padding:20px;
border:2px green solid;
float:left;
}

.chart {
	margin-bottom: 20px;
}
.chart .rank {
	width: 62px; height: 40px; margin-right: 10px; float: left;
}
.chart-wide h3 {
	font-size: 16px; margin-top: 0px; border-bottom-color: rgb(198, 198, 198); border-bottom-width: 5px; border-bottom-style: solid;
}
.chart .pos {
	width: 40px; text-align: center; color: white; line-height: 40px; font-size: 20px; font-weight: bold; margin-right: 2px; float: left; background-color: rgb(59,169,232);
}
.chart .num1 {
	width: 40px; text-align: center; color: white; line-height: 40px; font-size: 20px; font-weight: bold; margin-right: 2px; float: left; background-color: rgb(204,0,51);
}
.chart .num2 {
	width: 40px; text-align: center; color: white; line-height: 40px; font-size: 20px; font-weight: bold; margin-right: 2px; float: left; background-color: rgb(255,204,0);
}
.chart .num3 {
	width: 40px; text-align: center; color: white; line-height: 40px; font-size: 20px; font-weight: bold; margin-right: 2px; float: left; background-color: rgb(153, 0, 102);
}
.container > :first-child.row {
	margin-top: 15px;
}
.chart .date {
	font-size: 11px;
	font-weight: normal;
	margin-left: 5px;
}

h3 {
	color: rgb(70, 70, 70); line-height: 18px; font-size: 13px;
}
h4 {
    color: rgb(70, 70, 70);
    line-height: 0px;
    font-size: 13px;
}
h5 {
    color: rgb(70, 70, 70);
    line-height: 18px;
    font-size: 13px;
}
.chart-no-tab ol.items {
	border-top-color: rgb(198, 198, 198); border-top-width: 1px; border-top-style: solid;
}
.chart ol.items li {
	padding: 6px; border-top-color: rgb(229, 229, 229); border-top-width: 1px; border-top-style: solid;
}


.chart ol.items {
	border-width: 0px 1px 1px; border-style: none solid solid; border-color: currentColor rgb(198, 198, 198) rgb(198, 198, 198); list-style: none; margin: 0px; padding: 10px 2px 2px; border-image: none;
}
.chart ol.items li {
	padding: 6px; border-top-color: rgb(229, 229, 229); border-top-width: 1px; border-top-style: solid;
}
.chart ol.items li::before {
	line-height: 0; display: table; content: "";
}

.chart .icon-trend {
	height: 20px; float: left; background-color: rgb(245, 245, 245);
}
.icon-trend {
	width: 20px; height: 20px;
}
.icon-trend-up {
	background-position: 0px -80px;
}
.chart .last {
	height: 20px; float: left; background-color: rgb(245, 245, 245);
}
.chart .last {
	width: 20px; text-align: center; line-height: 20px; margin-top: 1px;
}

.chart-no-tab ol.items {
    border-top-color: rgb(198, 198, 198);
    border-top-width: 1px;
    border-top-style: solid;
}

.non-link-type {
    color: rgb(160, 160, 160) !important;
    cursor: text !important;
}
.chart .artist {
    font-weight: normal;
}
.chart .artist {
    margin: 0px;
    line-height: 20px;
}
.chart .artist {
    overflow: hidden;
    white-space: nowrap;
    position: relative;
    -ms-text-overflow: ellipsis;
    -o-text-overflow: ellipsis;
}

.chart ol.items li::after {
    clear: both;
}
.chart ol.items li::after {
    line-height: 0;
    display: table;
    content: "";
}
.date {
    color: rgb(160, 160, 160);
}
</style>
<div class="row">
    <div class="box col-md-8">
	
        <div class="box-inner">
            <div class="box-header well">
                <h2><i class="glyphicon glyphicon-info-sign"></i> <?php echo $page_name;?></h2>

                <div class="box-icon">
                    <a href="#" class="btn btn-minimize btn-round btn-default"><i
                            class="glyphicon glyphicon-chevron-up"></i></a>
                    <a href="#" class="btn btn-close btn-round btn-default"><i
                            class="glyphicon glyphicon-remove"></i></a>
                </div>
            </div>
            <div class="box-content row">
                <div class="col-lg-12 col-md-12">
					<div class="page">
						<div class="container">    
							<div class="row">
								<div class="row-fluid">
									<div  id ='div1'>
										<div class="row-fluid">
											<div class="span6">
												<div class="chart chart-no-tab chart-wide"  >
												<h3>上傳工作卡排行榜 <span class="date"><?php echo date("Y-m-d"); ?></span></h3>
												<div id="daily-chart-single">
													<ol class="items">
														<?php foreach ($files_ranking as $no => $data){?>
														
														<li class="" data-type="release_song">
															<div class="rank">
																<?php if($no <3 ) {?>
																	<div class="num<?php echo $no+1;?>"><?php echo $no+1;?></div>
																<?php }else{ ?>
																	<div class="pos"><?php echo $no+1;?></div>
																<?php } ?>
																<div class="icon-trend icon-trend-up"></div>
																<div class="last up"></div>
															</div>
															
															<h4 class="name"><?php echo $data['author'];?></h4>
															<h5 class="artist"><span class="non-link-type">上傳檔案數 : <?php echo $data['count'];?></span></h5>
														</li>													
														
														<?php }?>
													</ol>
												</div>
												
											</div>
										</div>
									</div>
									<!--
									<div id ='div2'>
										<div class="row-fluid">
											<div class="span6">
												<div class="chart chart-no-tab chart-wide"  >
												<h3>已審核工作卡排行榜 <span class="date"><?php echo date("Y-m-d"); ?></span></h3>
												<div id="daily-chart-single">
													<ol class="items">
														<?php foreach ($review_ranking as $no => $data){?>
														<li class="" data-type="release_song">
															<div class="rank">
																<?php if($no <3 ) {?>
																	<div class="num<?php echo $no+1;?>"><?php echo $no+1;?></div>
																<?php }else{ ?>
																	<div class="pos"><?php echo $no+1;?></div>
																<?php } ?>
																<div class="icon-trend icon-trend-up"></div>
																<div class="last up"></div>
															</div>
															
															<h4 class="name"><?php echo $data['author'];?></h4>
															<h5 class="artist"><span class="non-link-type">已審核檔案數 : <?php echo $data['count'];?></span></h5>
														</li>													
														
														<?php }?>
													</ol>
												</div>
												
											</div>
										</div>
									</div>									
									-->
								</div>	
							</div>			
						</div>
					</div>				
				</div>
			</div>
		</div>
    </div>
</div>				
</div>		
		

		
		</div></div></div>	
<!--
<div id="container" style="min-width: 510px; height: 400px; margin: 0 auto"></div>

	<table id="datatable">
		<thead>
			<tr>
				<th></th>
				<th>上傳檔案數</th>
			</tr>
		</thead>
		<tbody>
		
			<?php //foreach ($files_ranking as $data){
			?>
				<tr>
					<th><?php //echo $data['author'];?></th>
					<td><?php //echo $data['count'];?></td>
				</tr>
			<?php //}?>        
		</tbody>
	</table>


	<script type="text/javascript">

		Highcharts.chart('container', {
			data: {
				table: 'datatable'
			},
			chart: {
				type: 'column'
			},
			title: {
				text: '上傳檔案數排行榜'
			},
			yAxis: {
				allowDecimals: false,
				title: {
					text: ''
				}
			},
			tooltip: {
				formatter: function () {
					return '<b>' + this.series.name + '</b><br/>' +
						this.point.y + ' ' + this.point.name.toLowerCase();
				}
			}
		});
	</script>				
                </div>
-->               

           

