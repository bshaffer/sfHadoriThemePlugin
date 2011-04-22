$(document).ready(function() {
  $('.filter-select').change(function(){
    if(this.value) { 
      $('.filter-form .'+this.value).show(); 
      $('.filter-form .'+this.value+' .filter-include').attr('checked', true);
      $('.filter-form .'+this.value+' .filter-input').show();
    }
  });
  
  $('.filter-include').click(function(){
    if (this.checked) {
      $(this).parents('tr').find('.filter-input').show();
    }
    else {
      $(this).parents('tr').find('.filter-input').hide();
    };
  });
  
  $('th.batch input').click(function() {
    $('td input').attr('checked', this.checked);
    return true;
  });
  
 $('.filters legend').click(function() {
   $(this).parents('fieldset').toggleClass('collapsed').find('div.inner').slideToggle();
 });
 
 $('#information tbody tr:first a.promote').hide();
 $('#information tbody tr:last a.demote').hide();
 
  $('.info-block dt:first, .info-block dd:first').addClass('first');
});


