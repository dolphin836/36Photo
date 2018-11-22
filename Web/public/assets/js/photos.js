document.addEventListener('DOMContentLoaded', () => {
    var appElement  = document.getElementById("photo");
    var photoLayout = require('justified-layout');
    var max         = document.body.clientWidth, // 页面可视区域宽度
        topSpace    = 10,
        bottomSpace = 60,
        leftSpace   = 20,
        rightSpace  = 20;

    var records = photoLayout(photos, {
          containerWidth: max,
        containerPadding: {
               top: topSpace,
             right: rightSpace,
              left: leftSpace,
            bottom: bottomSpace
        },
        boxSpacing: 20,
        showWidows: is_show,
        targetRowHeight: 240
    });

    var photosElement               = document.createElement("div");
    photosElement.className         = "photos";
    photosElement.style.position    = "relative";

    records.boxes.map(function (record, i) {
        var photoElement            = document.createElement("div");
        photoElement.className      = "photo progressive";
        photoElement.style.width    = record.width  + "px";
        photoElement.style.height   = record.height + "px";
        photoElement.style.top      = record.top    + "px";
        photoElement.style.left     = record.left   + "px";
        photoElement.style.position = "absolute";

        photosElement.appendChild(photoElement);

        var aElement = document.createElement("a");
        aElement.setAttribute("href", '/photo/' + photos[i]['hash']);
        aElement.setAttribute("target", '_blank');

        photoElement.appendChild(aElement);

        var picElement = document.createElement("img");
        picElement.setAttribute("src", photos[i]['small']);
        picElement.setAttribute("data-progressive", photos[i]['large']);
        picElement.className = "progressive__img progressive--not-loaded";

        aElement.appendChild(picElement);
    });
    
    appElement.appendChild(photosElement);
    photosElement.style.height = records.containerHeight + "px";

    progressively.init();
}); 