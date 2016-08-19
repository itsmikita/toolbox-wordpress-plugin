<div class="wrap">
	<h1>{{ title }}</h1>
	
	{{# hasTabs }}
	<h2 class="nav-tab-wrapper">
		{{# tabs }}
		<a class="nav-tab nav-tab-{{ tab.name }} {{# tab.selected }}nav-tab-active{{/ tab.selected }} {{ tab.class }}" href="options-general.php?page={{ slug }}&tab={{ tab.name }}">{{ tab.title }}</a>
		{{/ tabs }}
	</h2>
	{{/ hasTabs }}
	
	<form method="post" action="options.php">
		{{{ hidden }}}
		{{# sections }}
			{{# section.title }}
				<h2 class="title">{{ . }}</h2>
			{{/ section.title }}
			
			{{# section.description }}
				<p>{{{ . }}}</p>
			{{/ section.description }}
			
			<table class="form-table">
				<tbody>
					{{# section.fields }}
					<tr>
						<th scope="row">{{ field.title }}</th>
						<td>
							{{# field.text }}
								<input type="text" name="{{ field.name }}" value="{{ field.value }}" class="{{ field.class }}" autocomplete="off" placeholder="{{ field.placeholder }}">
							{{/ field.text }}
							
							{{# field.password }}
								<input type="password" name="{{ name }}" value="{{ selected }}" class="{{ class }}" autocomplete="off" placeholder="{{ placeholder }}">
							{{/ field.password }}
							
							{{# field.dropdown }}
								<select name="{{ field.name }}" class="{{ field.class }}">
									{{# field.options }}
										<option value="{{ value }}" {{# selected }}selected="selected"{{/ selected }}>{{ label }}</option>
									{{/ field.options }}
								</select>
							{{/ field.dropdown }}
							
							{{# field.radio }}
								{{# field.options }}
									<p>
										<label>
											<input type="radio" name="{{ field.name }}" value="{{ value }}" {{# selected }}checked="checked"{{/ selected }} class="{{ class }}"> {{{ label }}}
										</label>
									</p>
								{{/ field.options }}
							{{/ field.radio }}
							
							{{# field.checkbox }}
								<p>
									<input type="hidden" name="{{ field.name }}" value="0">
									<label>
										<input type="checkbox" name="{{ field.name }}" value="1" {{# field.checked }}checked="checked"{{/ field.checked }} class="{{ class }}"> {{{ field.label }}}
									</label>
								</p>
							{{/ field.checkbox }}
							
							{{# field.checkboxes }}
								<fieldset>
									<legend class="screen-reader-text">
										<span>{{ title }}</span>
									</legend>
									
									{{# field.options }}
										<label>
											<input type="hidden" name="{{ option.name }}" value="0">
											<input type="checkbox" name="{{ option.name }}" value="1" {{# option.checked }}checked="checked"{{/ option.checked }} class="{{ option.class }}"> {{{ option.label }}}
										</label><br>
										{{# option.description }}
										<span class="description">{{{ . }}}</span><br>
										{{/ option.description }}
									{{/ field.options }}
								</fieldset>
							{{/ field.checkboxes }}
							
							{{# field.table }}
								<table class="wp-list-table widefat fixed striped posts {{ class }}">
									<thead>
										<tr>
											{{# list.columns }}
											<th class="column-{{ name }}">{{{ title }}}</th>
											{{/ list.columns }}
										</tr>
									</thead>
									<tbody {{# id }}id="{{ . }}"{{/ id }}>
										{{# list.rows }}
											{{> admin/settings/field/list-row }}
										{{/ list.rows }}
									</tbody>
								</table>
								
								{{# list.addRowButton }}
								<p><a href="#" class="button {{ list.addRowButtonClass }}">Add row</a></p>
								{{/ list.addRowButton }}
							{{/ field.table }}
							
							{{# field.html }}
								{{{ . }}}
							{{/ field.html }}
							
							{{# field.button }}
							<a href="#" class="button button-primary button-hero fortnox-bulk-action" {{# field.data }}data-{{ key }}="{{ value }}" {{/ field.data }}style="text-align: center; width: 240px">{{ field.button.text }}</a> <span class="spinner fortnox-spinner hero"></span>
							{{/ field.button }}
							
							{{# field.after }}
							{{{ . }}}
							{{/ field.after }}
							
							{{# field.description }}
								<p class="description">{{{ . }}}</p>
							{{/ field.description }}
						</td>
					</tr>
					{{/ section.fields }}
				</tbody>
			</table>
		{{/ sections }}
		
		{{# saveButton }}
		<p class="submit">
			<button class="button-primary">{{# i18n }}Save changes{{/ i18n }}</button>
		</p>
		{{/ saveButton }}
	</form>
</div>
