<?php $this->extend('main.layout.php'); ?>
<?php
	$editables = $page->getEditablesObject();
?>
<form action="<?php echo $this->routing->url('/Pages/save?page=' . $page->getName()); ?>" method="post" enctype="multipart/form-data">

	<div class="block bold">
		<div class="block_head">
			<h2>
				<?php echo $page->getAdminTitle(); ?>
				<span><?php echo $page->getAdminDescription(); ?></span>
			</h2>
			<input type="button" class="submit small" value="חזרה" />
		</div>	
		<div class="block_content">			
			<p>
				<input type="submit" disabled="disabled" class="submit small" value="שמור" />
				<input type="button" class="submit long" value="בטל את כל השינויים" />
				<input type="button" class="submit long" value="היסטוריית שינויים" />
			</p>
		</div>
	</div>

	<?php if ($editMeta = $page->canEditMeta()): ?>
		<div class="block">
			<div class="block_head">
				<h2>
הגדרות עמוד
					<span>כאן תוכלו לשנות הגדרות בעמוד. רצוי להשתמש בחלק זה רק מתוך ידיעה.</span>
				</h2>
			</div>	
			<div class="block_content">
				<p>
					<label>כותרת העמוד:</label>
					<input type="text" class="text medium" name="page_title" value="<?php echo $page->getTitle(); ?>" /> 
				</p>
				<?php if ($editMeta == Page::META_EDIT_ALL): ?>
					<p>חלק זה מיועד לאופטימיזציה עבור מנועי חיפוש:</p>
					<p>
						<label>תיאור העמוד:</label>
						<input type="text" class="text medium" name="meta_description" value="<?php echo $page->getMetaDescription(); ?>" /> 
					</p>
					<p>
						<label>מילות מפתח:</label>
						<input type="text" class="text medium" name="meta_keywords" value="<?php echo $page->getMetaKeywords(); ?>" /> 
					</p>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($page->getName() == 'homepage'): ?>
		<div class="block">
			<div class="block_head">
				<h2>
					טקסט ראשי
					<span>זהו הטקסט המוצג במרכז העמוד הראשי</span>
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p>
					<?php echo $editables->get('welcome')->admin(); ?>
				</p>
			</div>
		</div>
		<div class="block">
			<div class="block_head">
				<h2>
פרטי יצירת קשר
					<span>זהו הטקסט המופיע בחלונית צור הקשר הכתומה שבעמוד הראשי</span>
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<div class="message info" style="display: block;">
					<p>שינוי ממתין לשמירה. בוצע ע"י בוב דיאם ב-<?php echo date('d/m/Y H:i', time()-87654); ?><input type="button" class="submit tiny" value="בטל שינוי" /></p>
				</div>
				<p>
					<?php echo $editables->get('contact')->admin(); ?>
				</p>
			</div>
		</div>
	<?php elseif($page->getName() == 'about'):?>
		<div class="block">
			<div class="block_head">
				<h2>
טקסט ראשי
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p><?php echo $editables->get('main_text')->admin(); ?></p>
			</div>
		</div>
		<div class="block">
			<div class="block_head">
				<h2>
תמונה ראשית
<span>זוהי התמונה המופיעה בצד ימין של העמוד</span>
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p class="picture">
					<label>תמונה:</label>
					<?php echo $editables->get('side_picture')->admin(); ?>
				</p>
				<?php /*?>
				<p>
					<label>החלף בתמונה חדשה:</label>
					<input type="button" class="submit mid" value="העלאת קובץ" />
					<input type="button" class="submit long" value="תמונה מהאינטרנט" />
				</p>
				*/ ?>
				<p>
					<label>תיאור אלטרנטיבי:</label>
					ללא
				</p>
				<p>
					<label>כותרת <span>(מופיעה כאשר עוברים על גבי התמונה. ניתן להשאיר ריק)</span>:</label>
					ללא
				</p>
			</div>
		</div>
	<?php elseif($page->getName() == 'expertise' && !isset($_GET['e'])):?>
		<div class="block">
			<div class="block_head">
				<h2>
טקסט ראשי
				<span>זהו הטקסט המופיע כאשר נכנסים לעמוד בטרם לוחצים על התמחות מסויימת.</span>
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p>
					<label>כותרת:</label>
					<?php echo $editables->get('main_header')->admin(); ?>
				</p>
				<p>
					<label>טקסט:</label>
					<?php echo $editables->get('main_text')->admin(); ?>
				</p>
			</div>
		</div>
		<div class="block">
			<div class="block_head">
				<h2>
תחומי ההתמחות
					<span>בסעיף זה תוכל לערוך את כל תחומי ההתמחויות</span>
				</h2>
				<a class="btn edit" href="<?php echo $this->routing->url('/Pages/show?page=expertise&e=1'); ?>" title="Edit"></a>
			</div>
			<div class="block_content">
				<div class="message info" style="display: block;">
					<p>שינוי ממתין לשמירה. בוצע ע"י בוב דיאם ב-<?php echo date('d/m/Y H:i', time()-87654); ?><input type="button" class="submit tiny" value="בטל שינוי" /></p>
				</div>
				<div class="message info" style="display: block;">
					<p>שינוי ממתין לשמירה. בוצע ע"י בוב דיאם ב-<?php echo date('d/m/Y H:i', time()-87654); ?><input type="button" class="submit tiny" value="בטל שינוי" /></p>
				</div>
				<p>ישנם 5 פריטים במערך זה.</p>
			</div>
		</div>
	<?php elseif($page->getName() == 'expertise' && isset($_GET['e']) && $_GET['e']==1):?>
		<div class="block bold">
			<div class="block_head">
				<h2>
תחומי ההתמחות
					<span>בסעיף זה תוכל לערוך את כל תחומי ההתמחויות</span>
				</h2>
				<input type="button" class="submit small" value="הוסף" />
				<input type="button" class="submit small" value="מחק הכל" />
			</div>
			<div class="block_content">
				<ul class="pagination">
					<li><input type="button" class="submit small" value="<" disabled="disabled" /></li>
					<li><input type="button" class="submit small" value="1" /></li>
					<li><input type="button" class="submit small" value=">" disabled="disabled" /></li>
				</ul>
			</div>
		</div>
	
		<div class="block">
			<div class="block_head">
				<h2>
					התמחות א'
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p>
					<label>כותרת:</label>
					כותרת
				</p>
				<p>
					<label>טקסט:</label>
טקסט
				</p>
				<p>
					<label>תתי התמחות:</label>
ישנם 3 פריטים במערך זה.
				</p>
			</div>
		</div>
		<div class="block">
			<div class="block_head">
				<h2>
תת התמחות
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p>
					<label>כותרת:</label>
					כותרת
				</p>
				<p>
					<label>טקסט:</label>
טקסט
				</p>
				<p>
					<label>תתי התמחות:</label>
ישנם 3 פריטים במערך זה.
				</p>
			</div>
		</div>
		<div class="block">
			<div class="block_head">
				<h2>
תת התמחות
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p>
					<label>כותרת:</label>
					כותרת
				</p>
				<p>
					<label>טקסט:</label>
טקסט
				</p>
				<p>
					<label>תתי התמחות:</label>
ישנם 3 פריטים במערך זה.
				</p>
			</div>
		</div>
		<div class="block">
			<div class="block_head">
				<h2>
					התמחות ב'
				</h2>
				<a class="btn edit" href="#" title="עריכה"></a>
			</div>	
			<div class="block_content">	
				<p>
					<label>כותרת:</label>
					כותרת
				</p>
				<p>
					<label>טקסט:</label>
טקסט
				</p>
				<p>
					<label>תתי התמחות:</label>
ישנם 3 פריטים במערך זה.
				</p>
			</div>
		</div>
	<?php elseif($page->getName() == 'contact'):?>
		<div class="block">
			<div class="block_head">
				<h2>
פרטי יצירת קשר
	<span>זהו הטקסט המופיע בצד ימין של העמוד</span>
				</h2>
			</div>	
			<div class="block_content">	
				<p>
					<textarea class="wysiwyg" name="address"><?php echo $editables->get('address'); ?></textarea>
				</p>
			</div>
		</div>
	<?php elseif($page->getName() == 'terms'):?>
		<div class="block">
			<div class="block_head">
				<h2>
תוכן
				</h2>
			</div>	
			<div class="block_content">	
				<p>
					<label>כותרת:</label>
					<input type="text" class="text medium" name="title" value="<?php echo $editables->get('title'); ?>" /> 
				</p>
				<p>
					<label>טקסט:</label>
					<textarea class="wysiwyg" name="text"><?php echo $editables->get('text'); ?></textarea>
				</p>
			</div>
		</div>	
	<?php endif; ?>
</form>