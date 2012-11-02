$(document).bind("ready", function init() {
	$(document).on("click", ".post .expand", function() {
		$(this).siblings(".body").toggleClass("expanded")
		$(this).remove()
	})

	$(document).on("click", ".comments .show-more", function() {
		for (var i = 0; i < 4; i++) {
			$(this).next(".comment").clone().insertAfter(this)
		}
		$(this).remove()
	})

    $.fn.message = function(text) {
        var error = $("<div>", {
            "class": "message"
        }).append($("<div>", {
            text: text
        }))
        error.insertAfter(this)
        error.css("width", this.outerWidth())
        error.position({
            of: this,
            my: "center bottom",
            at: "center top"
        })
        error.hide()
        return error
    }

    $(".auth, .profileForm").on("focusin focusout", "input", function(e) {
        if (e.type == "focusin") {
            $(e.target).next(".message").fadeIn();
        } else {
            $(e.target).next(".message").fadeOut();
        }
    })

    $.fn.validateAttrs = function(data) {
        this.find("input").removeClass("error").next(".message").remove()
        for (attr in data)
        {
            $("#"+attr).addClass("error").message(data[attr]).fadeIn().delay(1500).fadeOut();
        }
    }
})