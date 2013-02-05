
/**
 * Image sort wizard
 * @param object
 * @param string
 * @param string
 */
Backend.LanguageTextField = function(el, id){
	var list = $(id);
	var items = list.getChildren();
	Backend.getScrollOffset();
	
	var lf_lng_list = $(el).getParent().getChildren('.lf_lng_list')
	var default_left = lf_lng_list.getStyle('left');
	
	lf_lng_list.setStyle('left', 0);
	console.log('x');
	
	
	
	
	/*switch(command)
	{
		case 'up':
			parent.getPrevious() ? parent.injectBefore(parent.getPrevious()) : parent.injectInside(list);
		break;

		case 'down':
			parent.getNext() ? parent.injectAfter(parent.getNext()) : parent.injectBefore(list.getFirst());
		break;
	}*/
}
