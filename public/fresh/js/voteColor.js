
$(document).ready(colorVotes);

function colorVotes(){
    
    $(".reviewVote").each(function(index){
        var color = getColorFromVote( $(this).text());
         this.style.color = color;
         //$(this).css("-webkit-text-fill-color",color);
         //$(this).css("text-shadow","0px 0px 0px "+color+", inset -2px -2px 0px "+shadeColor(color,-30)+";");
    });
    
}

function shadeColor(color, percent) {
        var num = parseInt(color.slice(1), 16), amt = Math.round(2.55 * percent), R = (num >> 16) + amt, B = (num >> 8 & 0x00FF) + amt, G = (num & 0x0000FF) + amt;
        return "#" + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 + (B < 255 ? B < 1 ? 0 : B : 255) * 0x100 + (G < 255 ? G < 1 ? 0 : G : 255)).toString(16).slice(1);
}


function getColorFromVote(vote){
    vote = parseFloat(vote);
    /*
    if(vote >= 0.0 && vote < 1.5){
        return "#CF0000";
    }
    else if(vote >= 1.5 && vote < 2.5){
        return "#D93900";
    }
    else if(vote >= 2.5 && vote < 4.5){
        return "#ea6700";
    }
    else if(vote >= 4.5 && vote < 5.5){
        return "#ea9b00";
    }
    else if(vote >= 5.5 && vote < 6){
        return "#eaab23";
    }
    else if(vote >= 6.0 && vote < 7.0){
        return "#ffe400";
    }
    else if(vote >= 7.0 && vote < 8.0){
        return "#bce400";
    }
    else if(vote >= 8.0 && vote < 9.0){
        return "#7de400";
    }
    else if(vote >= 9.0 && vote <= 10.0){
        return "#00e400";
    }
    */
   if( vote < 2.5){
        return "#c20f0f";
    }
    else if(vote >= 2.5 && vote < 3.5){
        return "#c2300f";
    }
    else if(vote >= 3.5 && vote < 4.5){
        return "#ea6700";
    }
    else if(vote >= 4.5 && vote < 5.5){
        return "#c15009";
    }
    else if(vote >= 5.5 && vote < 6.2){
        return "#c27c03";
    }
    else if(vote >= 6.2 && vote < 7.0){
        return "#c2b703";
    }
    else if(vote >= 7.0 && vote < 8.0){
        return "#afc203";
    }
    
    else if(vote >= 8.0 && vote < 9.0){
        return "#82d905";
    }
    else if(vote >= 9.0){
        return "#58d905";
    }
   
   
   
    return "black";
}

