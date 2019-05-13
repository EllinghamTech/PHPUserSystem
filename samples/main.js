/*
 * Copyright (c) 2019. Ellingham Technologies Ltd
 * Website: https://ellinghamtech.co.uk
 * Developer Site: https://ellingham.dev
 *
 * MIT License
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

String.prototype.capitalize = function() {
	return this.charAt(0).toUpperCase() + this.slice(1);
};

jQuery(document).ready(function()
{
	jQuery('code').html(function() {
		return this.innerHTML.replace(/\t/g, '    ');
	});

	jQuery('code').each(function(index)
	{
		var lines = jQuery(this).html().split(/\n/);
		var original = jQuery(this).html();

		var html = '';

		jQuery.each(lines, function(i, v)
		{
			html += '<div class="line">';
			html += '<div class="inner">';
			html += '<div class="number">' + (1+i) + '</div>';
			html += '<div class="content">' + v + '</div>';
			html += '</div>';
			html += '</div>';
		});

		jQuery(this).html(html);

		// Annotations
		jQuery(this).find('.annotation').each(function(i, v)
		{
			var annot = '';

			attr = jQuery(this).attr('data-note');
			if(typeof attr !== typeof undefined && attr !== false)
			{
				annot += '<span class="note">Note: ' + jQuery(this).attr('data-note') + '</span>';
			}

			var lineContainer = jQuery(this).parent().parent().parent();
			var lineHtml = lineContainer.find('.inner .content').html();
			var lineLeadingSpaces = lineHtml.match(/^\s*/)[0].length;

			var appendSpaces = '';

			for(var x=0; x<lineLeadingSpaces; x++)
			{
				appendSpaces += '&nbsp;';
			}

			var html = '<div class="inner annot">';
			html += '<div class="number"></div>';
			html += '<div class="content">'+appendSpaces+annot+'</div>';
			html += '</div>';

			lineContainer.prepend(html);
		});

		jQuery(this).append('<div class="text">' + original + '</div>');
		jQuery(this).find('.annotations').remove();

		var controlHtml = '<div class="code_control">';
		controlHtml += '<a href="javascript:void(0)" onclick="jQuery(this).parent().parent().find(\'.line\').toggle(0);jQuery(this).parent().parent().find(\'.text\').toggle(0);">Toggle Pretty/Text View</a>';
		controlHtml += '<a href="javascript:void(0)" onclick="jQuery(this).parent().parent().find(\'.annot\').toggle(0);">Show/Hide Annotations</a>';
		controlHtml += '<div>';
		jQuery(this).prepend(controlHtml);
	});
});
