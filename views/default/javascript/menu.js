function initMenu(data,current,url)
{
	for(i in data)
	{
            if(data[i]['current']){
    			var list = data[i]['list'];
                var item = '';
                item += '<h6 id="h-menu-products" class="selected"><a href="'+data[i]['link']+'"><span>'+data[i]['title']+'</span></a></h6>';
                item += '<ul id="menu-products" class="openned">';
                for(j in list)
                {
                    if(list[j].urlPathinfo == current){
                    	item +='<li class="selected"><a href="'+j+'">'+list[j].name+'</a></li>';
                    }else{
                    	item +='<li><a href="'+j+'">'+list[j].name+'</a></li>';
                    }
                    
                }
                item += '</ul>';
                $('#menu').append(item);
           }else{
        	   var list = data[i]['list'];
               var item = '';
               item += '<h6 id="h-menu-products" class="opnned"><a href="'+data[i]['link']+'"><span>'+data[i]['title']+'</span></a></h6>';
               item += '<ul id="menu-products" class="closed">';
               for(j in list)
               {
            	   if(list[j].urlPathinfo == current){
                   		item +='<li class="selected"><a href="'+j+'">'+list[j].name+'</a></li>';
                   }else{
                   		item +='<li><a href="'+j+'">'+list[j].name+'</a></li>';
                   }
                   
               }
               item += '</ul>';
               $('#menu').append(item);
           }
	}

}
