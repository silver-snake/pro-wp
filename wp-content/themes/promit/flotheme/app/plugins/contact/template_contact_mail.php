From: <?php echo $values['name']?> <<?php echo $values['email']?>>
<?php $configs = Flotheme_Config::get();?>
<?php foreach($configs['contact']['fields'] as $key => $val):?>
	<?php echo $key?>: <?php echo $values[$key]?>

<?php endforeach?>
---

This mail is sent via contact form on your Wordpress Blog