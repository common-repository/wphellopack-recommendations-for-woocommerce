class wphellopack_Recommender{constructor(e,t){this.configuration={cookieName:"wphellopack_guest_recommendation",savedCategoriesNumberLimit:10,maxCalculatedCategoriesNumberLimit:3,defaultStrategyKey:"1"},this.recommendationHolder=e,this.product=t,this.productCategories=new Array}saveUserStep(){this.productCategories=this.getCookie(this.configuration.cookieName),0==this.productCategories?(this.productCategories=new Array,this.productCategories.push(this.product.categoryId),this.setCookie(this.configuration.cookieName,JSON.stringify(this.productCategories),1)):(this.productCategories=JSON.parse(this.productCategories),this.productCategories=this.productCategories.slice(0,this.configuration.savedCategoriesNumberLimit-1),this.productCategories.push(this.product.categoryId),this.setCookie(this.configuration.cookieName,JSON.stringify(this.productCategories),1))}getStrategyKey(){for(var e={},t=0;t<this.productCategories.length;++t)e[this.productCategories[t]]?e[this.productCategories[t]]++:e[this.productCategories[t]]=1;var r=[];for(var i in e)r.push([i,e[i]]);r.sort((function(e,t){return e[1]-t[1]}));var o=r.slice(-1*parseInt(this.configuration.maxCalculatedCategoriesNumberLimit)).reverse(),s=new Array;for(var a in o)Number.isInteger(parseInt(o[a][0]))&&s.push(o[a][0]);return s.join("-")}getCookie(e){e+="=";for(var t=document.cookie.split(";"),r=0;r<t.length;r++){for(var i=t[r];" "==i.charAt(0);)i=i.substring(1);if(0==i.indexOf(e))return i.substring(e.length,i.length)}return!1}setCookie(e,t,r){var i=new Date;i.setTime(i.getTime()+24*r*60*60*1e3);var o="expires="+i.toUTCString();document.cookie=e+"="+t+"; "+o+"; path=/"}fetch(){var e=this.recommendationHolder,t=this.getStrategyKey();if(t||this.configuration.defaultStrategyKey){var r=new XMLHttpRequest,i="/index.php/wp-json/wphellopack-recommendations/v1/products?strategy="+this.getStrategyKey();r.open("GET",i,!0),r.onreadystatechange=function(){4===r.readyState&&200===r.status&&(document.getElementById(e).innerHTML=r.responseText)},r.send()}}}