var province=$("#province"),city=$("#city"),town=$("#town");
var province1=$("#province1"),city1=$("#city1"),town1=$("#town1");
var province3=$("#province3"),city3=$("#city3"),town3=$("#town3");
for(var i=0;i<provinceList.length;i++){
    addEle(province,provinceList[i].name,provinceList[i].id);
}
function addEle(ele,value,id){
    var optionStr="";
    optionStr="<option value="+id+">"+value+"</option>";
    ele.append(optionStr);
}
function removeEle(ele){
    ele.find("option").remove();
    var optionStar="<option value="+"0"+">"+"请选择"+"</option>";
    ele.append(optionStar);
}
var provinceText,cityText,cityItem;
province.on("change",function(){
    provinceText=$(this).val();

    $.each(provinceList,function(i,item){
        if(provinceText == item.id){
            cityItem=i;
            return cityItem
        }
    });
    removeEle(city);
    removeEle(town);
    $.each(provinceList[cityItem].cityList,function(i,item){
        addEle(city,item.name,item.id)
    })
});
city.on("change",function(){
    cityText=$(this).val();
    removeEle(town);
    $.each(provinceList,function(i,item){
        if(provinceText == item.id){
            cityItem=i;
            return cityItem
        }
    });

    $.each(provinceList[cityItem].cityList,function(i,item){
        if(cityText == item.id){

            for(var n=0;n<item.areaList.length;n++){
                addEle(town,item.areaList[n].name,item.areaList[n].id)
            }
        }
    });
});



for(var i=0;i<provinceList.length;i++){
    addEle(province1,provinceList[i].name,provinceList[i].id);
}
function addEle(ele,value,id){
    var optionStr="";
    optionStr="<option value="+id+">"+value+"</option>";
    ele.append(optionStr);
}
function removeEle(ele){
    ele.find("option").remove();
    var optionStar="<option value="+"0"+">"+"请选择"+"</option>";
    ele.append(optionStar);
}
var provinceText,cityText,cityItem;
province1.on("change",function(){
    provinceText=$(this).val();
    $.each(provinceList,function(i,item){
        if(provinceText == item.id){
            cityItem=i;
            return cityItem
        }
    });
    removeEle(city1);
    removeEle(town1);
    $.each(provinceList[cityItem].cityList,function(i,item){
        addEle(city1,item.name,item.id)
    })
});
city1.on("change",function(){
    cityText=$(this).val();
    removeEle(town1);
    $.each(provinceList,function(i,item){
        if(provinceText == item.id){
            cityItem=i;
            return cityItem
        }
    });
    $.each(provinceList[cityItem].cityList,function(i,item){
        if(cityText == item.id){
            for(var n=0;n<item.areaList.length;n++){
                addEle(town1,item.areaList[n].name,item.areaList[n].id)
            }
        }
    });
});



for(var i=0;i<provinceList.length;i++){
    addEle(province3,provinceList[i].name);
}
function addEle(ele,value,id){
    var optionStr="";
    optionStr="<option value="+id+">"+value+"</option>";
    ele.append(optionStr);
}
function removeEle(ele){
    ele.find("option").remove();
    var optionStar="<option value="+"0"+">"+"请选择"+"</option>";
    ele.append(optionStar);
}
var provinceText,cityText,cityItem;
province3.on("change",function(){
    provinceText=$(this).val();
    $.each(provinceList,function(i,item){
        if(provinceText == item.name){
            cityItem=i;
            return cityItem
        }
    });
    removeEle(city3);
    removeEle(town3);
    $.each(provinceList[cityItem].cityList,function(i,item){
        addEle(city3,item.name,item.id)
    })
});
city3.on("change",function(){
    cityText=$(this).val();
    console.log(cityText);
    removeEle(town3);
    $.each(provinceList,function(i,item){
        if(provinceText == item.id){
            cityItem=i;
            return cityItem
        }
    });
    $.each(provinceList[cityItem].cityList,function(i,item){
        if(cityText == item.name){
            for(var n=0;n<item.areaList.length;n++){
                addEle(town3,item.areaList[n].name,item.areaList[n].id)
            }
        }
    });
});