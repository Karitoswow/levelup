var Items = {
    identifier: "item",
    Links: {
        remove: "levelup/admin/delete/",
        removeGroup: "levelup/admin/deleteGroup/",
        createGroup: "levelup/admin/createGroup/",
        save: "levelup/admin/save/",
        Url: "levelup/admin/",
    },
    	remove: function(id, element)
	{
		var identifier = this.identifier,
			removeLink = this.Links.remove;
		Swal.fire({
			title: 'Do you really want to delete this "' + identifier + '"?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		if (result.isConfirmed) {
			$("#" + identifier + "_count").html(parseInt($("#" + identifier + "_count").html()) - 1);

			$(element).parents("tr").slideUp(300, function()
			{
				$(this).remove();
			});

			$.get(Config.URL + removeLink + id, function(data)
			{
				console.log(data);
			});
		}
		})
	},
    add: function () {
        var id = this.identifier;

        if ($("#add_" + id).is(":visible")) {
            $("#add_" + id).fadeOut(150, function () {
                $("#main_" + id).fadeIn(150);
            });
        } else {
            $("#main_" + id).fadeOut(150, function () {
                $("#add_" + id).fadeIn(150);
            });
        }
    },
    addGroup: function () {
        var id = this.identifier;

        if ($("#add_group").is(":visible")) {
            $("#add_group").fadeOut(150, function () {
                $("#main_" + id).fadeIn(150);
            });
        } else {
            $("#main_" + id).fadeOut(150, function () {
                $("#add_group").fadeIn(150);
            });
        }
    },
    create: function (form) {
        var values = {
            csrf_token_name: Config.CSRF
        };
        $(form).find("input, select, textarea").each(function () {
            if ($(this).attr("type") == "checkbox") {
                values[$(this).attr("name")] = this.checked;
            } else if ($(this).attr("type") != "submit") {
                values[$(this).attr("name")] = $(this).val();
            }
        });

        Swal.fire({
            icon: 'success',
            title: 'Please wait ...',
            text: '',
        })

        $.post(Config.URL + this.Links.createGroup, values,
            function (data) {
                if (data == "1") {
                        window.location = '';
                } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Playtime',
                            text: data,
                        })
                }
            });
    },
    save: function (form, id) {
        var values = {
            csrf_token_name: Config.CSRF
        };
        $(form).find("input, select, textarea").each(function () {
            if ($(this).attr("type") == "checkbox") {
                values[$(this).attr("name")] = this.checked;
            } else if ($(this).attr("type") != "submit") {
                values[$(this).attr("name")] = $(this).val();
            }
        });

        $.post(Config.URL + this.Links.save + id, values, function (data) {
            console.log(data);
            eval(data);
        });
    },
    groupActions: function () {
        if (typeof formType != "undefined") {
            Items.formType = formType;
        }
        $(".item_group").each(function () {
            $(this).hover(
                function () {
                    $(this).find(".group_title").hide(0, function () {
                        $(this).siblings(".group_order").hide(0);
                        $(this).siblings(".group_actions").show(0);
                    });
                },
                function () {
                    $(this).find(".group_actions").hide(0, function () {
                        $(this).siblings(".group_order").show(0);
                        $(this).siblings(".group_title").show(0);
                    });
                }
            );
        });
    },
    formType: "item",
    fadeFormIn: function (name) {
        Items.formType = name;

        $("#" + name + "_form").fadeIn(300);
    },
}
$(document).ready(Items.groupActions);