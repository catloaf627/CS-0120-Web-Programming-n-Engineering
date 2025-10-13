function GalleryItem(name, price, thumb, large) {
  this.name = name;
  this.price = price;
  this.thumb = thumb;
  this.large = large;
}

let galleryItems = [
  new GalleryItem("Portrait of Madame X", "$80 million", "images/MadameX_thumb.jpg", "images/MadameX_large.jpg"),
  new GalleryItem("Marilyn Diptych", "$200 million", "images/Marilyn_thumb.jpg", "images/Marilyn_large.jpg"),
  new GalleryItem("Napoleon Crossing the Alps", "$20 million+", "images/Napoleon_thumb.jpg", "images/Napoleon_large.jpg"),
  new GalleryItem("Guernica", "Invaluable (museum-held)", "images/Guernica_thumb.jpg", "images/Guernica_large.jpg"),
  new GalleryItem("Les Chats et la Guitare", "$100,000", "images/cat_thumb.jpg", "images/cat_large.jpg")
];

$(document).ready(function() {
  // Populate thumbnails
  for (let i = 0; i < galleryItems.length; i++) {
    let img = $("<img>")
      .attr("src", galleryItems[i].thumb)
      .attr("data-index", i)
      .addClass("thumb");
    $("#thumbnails").append(img);
  }

  // --- HOVER: Show large image ---
  $("#thumbnails").on("mouseenter", ".thumb", function() {
    let index = $(this).data("index");
    let item = galleryItems[index];

    // Cancel any current hide animations
    $("#largeImage, #infoPanel").stop(true, true);

    // Clear and show new large image
    $("#largeImage").empty();
    let bigImg = $("<img>").attr("src", item.large);
    $("#largeImage").append(bigImg);
    bigImg.fadeIn(400);
  });

  // --- LEAVE: Hide both image and info panel ---
  $("#thumbnails").on("mouseleave", ".thumb", function() {
    $("#largeImage").fadeOut(300, function() {
      $(this).empty().show(); // ready for next hover
    });
    $("#infoPanel").stop(true, true).fadeOut(200);
  });

  // --- CLICK: Show price for 4 sec (only if still hovering) ---
  $("#thumbnails").on("click", ".thumb", function() {
    let thumb = $(this);
    let index = thumb.data("index");
    let item = galleryItems[index];

    // Ignore click if cursor not on this thumbnail
    if (!thumb.is(":hover")) return;

    $("#infoPanel")
      .stop(true, true)
      .html(`<strong>${item.name}</strong><br>Price: ${item.price}`)
      .fadeIn(300);

    // Hide after 4 seconds, only if still hovering
    setTimeout(function() {
      if (thumb.is(":hover")) {
        $("#infoPanel").fadeOut(800);
      }
    }, 4000);
  });
});
