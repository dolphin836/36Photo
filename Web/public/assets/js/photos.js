document.addEventListener('DOMContentLoaded', () => {
    var appElement  = document.getElementById("app");
    var photoLayout = require('justified-layout');
    var max         = document.body.clientWidth, // 页面可视区域宽度
        topSpace    = 80,
        bottomSpace = 80,
        leftSpace   = parseInt(max * 0.1),
        rightSpace  = leftSpace;

    var records = photoLayout(photos, {
          containerWidth: max,
        containerPadding: {
               top: topSpace,
             right: rightSpace,
              left: leftSpace,
            bottom: bottomSpace
        },
        boxSpacing: 20,
        showWidows: true
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
        picElement.className    = "progressive__img progressive--not-loaded";

        aElement.appendChild(picElement);
    });
    
    appElement.appendChild(photosElement);
    photosElement.style.height = records.containerHeight + "px";

    progressively.init();
}); 