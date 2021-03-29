<?
/* * ****************************************************************************
  #                         BookingWizz v5.2.1
  #******************************************************************************
  #      Author:     Convergine (http://www.convergine.com)
  #      Website:    http://www.convergine.com
  #      Support:    http://support.convergine.com
  #      Version:     5.2.1
  #
  #      Copyright:   (c) 2009 - 2012  Convergine.com
  #	   Icons from PixelMixer - http://pixel-mixer.com/basic_set/ and by Manuel Lopez - http://www.iconfinder.com/search/?q=iconset%3A48_px_web_icons
  #
  #****************************************************************************** */

	$week =(isset($_REQUEST["week"]))?strip_tags(str_replace("'","`",$_REQUEST["week"])):'';

					$step = 15;

?>
						<div>
							<select class='hh' name="week_from_h_<?php echo $week;?>[]">
								<option value="--">- -</option>		
								<?for($h = 0;$h <= 23 ;$h++){?>
									<option value="<?php echo $h*60;?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT);?></option>
								<?php } ?>
							</select>:
							<select class='mm' name="week_from_m_<?php echo $week;?>[]">
								<option value="--">- -</option>	
								<?for($m = 0;$m < 60 ;$m=$m+$step){?>
									<option value="<?php echo $m;?>"><?php echo str_pad($m, 2, "0", STR_PAD_LEFT);?></option>
								<?php } ?>
							</select>
                            &nbsp;to&nbsp;
							<select class='hh' name="week_to_h_<?php echo $week;?>[]">
								<option value="--">- -</option>	
								<?for($h = 0;$h <= 24 ;$h++){?>
									<option value="<?php echo $h*60;?>"><?php echo str_pad($h, 2, "0", STR_PAD_LEFT);?></option>
								<?php } ?>
							</select>:
							<select class='mm' name="week_to_m_<?php echo $week;?>[]">
								<option value="--">- -</option>	
								<?for($m = 0;$m < 60;$m=$m+$step){?>
									<option value="<?php echo $m;?>"><?php echo str_pad($m, 2, "0", STR_PAD_LEFT);?></option>
								<?php } ?>
							</select>
						</div>