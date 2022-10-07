//Loader Starts
var viHost = "https://www.canvasflip.com";
var projects = null;
var subscription = null;
var sortingProject = false;

function viLoadFunction() {
    ProjectsInit();
    LoginInit();

    //Search project
    jQuery("#viSearchProject").on("keyup", function (event) {
        event.stopPropagation();

        var searchQuery = jQuery(this).val().toLowerCase();
        var viProjectContainer = jQuery("#viProjectContainer");
        var viProjects = viProjectContainer.find(".vi-card").hide();

        if (searchQuery === "") {
            viProjects.fadeIn();
            return;
        }

        var count = 0;
        viProjectContainer.find(".vi-card").each(function () {
            var viProject = jQuery(this);
            if (viProject.find(".vi-project-name").text().toLowerCase().indexOf(searchQuery) > -1) {
                viProject.show();
                count++;
            }
        });
    });

    // Filter project 
    jQuery("#viProject").on("click", ".sort-by", function () {
        var sortBy = jQuery(this).attr("data-id");

        switch (sortBy) {
            case "last-edited":
                SortByDate("last");
                break;
            case "newsest-first":
                SortByDate("newest");
                break;
            case "a-z":
                SortByName("asc");
                break;
            case "z-a":
                SortByName("desc");
                break;
        }
    });

    //Show vi card overlay on vi card hover
    jQuery("#viProjectContainer").on("mouseover", ".vi-card", function () {
        jQuery(this).find(".vi-card-reveal").addClass("active");
        jQuery(this).find(".vi-project-details-right").addClass("active");
    });

    //Show vi card overlay on vi card hover
    jQuery("#viProjectContainer").on("mouseleave", ".vi-card", function () {
        jQuery(this).find(".vi-card-reveal").removeClass("active");
        jQuery(this).find(".vi-project-details-right").removeClass("active");
    });
}

//Sort by name
function SortByName(order, projectId) {
    var sortByFunction = null;
    if (order == "asc") {
        viProjectSortedByValue = "A - Z";
        sortByFunction = function (a, b) {
            var nameA = a.url.toLowerCase(), nameB = b.url.toLowerCase();
            if (nameA < nameB) //sort string ascending
                return -1;
            if (nameA > nameB)
                return 1;
            return 0; //default return value (no sorting)
        }
    } else {
        viProjectSortedByValue = "Z - A";
        sortByFunction = function (a, b) {
            var nameA = a.url.toLowerCase(), nameB = b.url.toLowerCase();
            if (nameA < nameB) //sort string descending
                return 1;
            if (nameA > nameB)
                return -1;
            return 0; //default return value (no sorting)
        }
    }
    if (!projectId) {
        $("#viProjectSortedBy").text(viProjectSortedByValue);
        //sort screen array with title
        $("#viProjectsContainer").empty();
        projects.sort(sortByFunction);
        sortingProject = true;
        renderProjects(projects, sortingProject);
    }
}

//Sort by date
function SortByDate(order, projectId) {
    var sortByFunction = null;
    if (order == "newest") {
        viProjectSortedByValue = "Newest First";
        sortByFunction = function (a, b) {
            return new Date(b.date.replace(/-/g, "/") + " UTC") - new Date(a.date.replace(/-/g, "/") + " UTC");
        }
    } else {
        viProjectSortedByValue = "Last Edited";
        sortByFunction = function (a, b) {
            return new Date(a.date.replace(/-/g, "/") + " UTC") - new Date(b.date.replace(/-/g, "/") + " UTC");
        }
    }

    if (!projectId) {
        $("#viProjectSortedBy").text(viProjectSortedByValue);
        //sort screen array with title
        $("#viProjectsContainer").empty();
        projects.sort(sortByFunction);
        sortingProject = true;
        renderProjects(projects, sortingProject);
    }
}

//get project data
function ProjectsInit() {
    jQuery.ajax({
        url: viHost + "/vi/svcs/getProjects.php",
        xhrFields: {withCredentials: true}, //to send cookies in case of inline edit
        type: "POST",
        cache: false,
        success: function (data) {
            if (data.result == "success") {
                projects = data.projects;
                subscription = data.subscription;

                if (subscription != null) {
                    //logged in; load projects
                    if (projects.length > 0) {
                        renderProjects(projects, sortingProject);
                    } else {
                        renderEmptyProjects();
                    }
                } else {
                    //not logged in; ask for login/signup
                    renderLogin();
                }
            }

            //close loading icon
            jQuery("#viStudioIcon").hide();
            jQuery("#viContainer").show();
        },
        error: function (data) {
            //..
        },
        fail: function (data) {
            //..
        }
    });
}

function renderLogin() {
    jQuery(".page").removeClass("active");
    jQuery(".vi-header").removeClass("active");
    jQuery("#viLogin").addClass("active");
    jQuery("#viLoginHeader").addClass("active");
}

//Render VI Projects
function renderEmptyProjects() {
    jQuery(".page").removeClass("active");
    jQuery("#viEmptyProjects").addClass("active");
    jQuery("#viProjectsHeader").addClass("active");
    jQuery(".vi-filer").show();
    jQuery("#viLoginHeader").hide();
    jQuery("#viWPProjectLabel").hide();
}

//Render VI Projects
function renderProjects(projects, sortingProject) {
    jQuery(".page").removeClass("active");
    jQuery(".vi-header").removeClass("active");
    jQuery("#viProject").addClass("active");
    jQuery("#viProjectsHeader").addClass("active");

    jQuery("#viWPProjectLabel").hide();
    jQuery(".vi-project-heading").hide();
    jQuery(".vi-project-found-title").css({"display": "none"});

    var projectDOM = "",
            wpProjectDOM = "",
            src = "";
    for (var i = 0; i < projects.length; i++) {
        if (projects[i].pages.length > 0) {
            src = "http://127.0.0.1:10000/devstoreaccount1/vi-screens/" + projects[i].pages[0].uuid + ".jpeg";
        } else {
            src = "";
        }
        if (sortingProject != true) {
            if (window.origin == projects[i].url) {
                wpProjectDOM = '<div data-id="' + projects[i].id + '" data-uuid="' + projects[i].uuid + '" class="row vi-project vi-card">' +
                        '<div class="col-md-3 col-sm-3 col-xs-12 vi-wpproject-name">' + projects[i].url + '</div>' +
                        '<div class="col-md-2 col-sm-2 col-xs-12 vi-wpproject-total-pages">' +
                        (projects[i].pages.length > 1 ? projects[i].pages.length + " Pages" : projects[i].pages.length + " Page") + '</div>' +
                        '<div class="col-md-7 col-sm-7 reset-sync-btn">';
                if (projects[i].wp_sync_date != null) {
                    wpProjectDOM += '<label id="vi-wpproject-last-synced"><span>Last Synced </span>' +
                            '<span class="timeago" title="' + new Date(projects[i].wp_sync_date.replace(/-/g, "/") + " UTC").toLocaleString() + '"></span></label>';
                }
                wpProjectDOM += '<label class="vi-wpproject-sync">' +
                        '<a class="vi-open-project-btn"> Sync</a>' +
                        '<a class="vi-reset-project-btn vi-project-reset"> Reset</a>' +
                        '</label></div>' +
                        '</div>' +
                        '<div class="vi-wpproject-image"><div><div>' +
                        '<img class="vi-project-img" src=' + viImageUrl + 'assets/images/empty-icon.png alt="image" data-src="' + src + '"/>' +
                        '</div></div></div>';
                jQuery(".vi-project-found-title").css({"display": "none"});
                jQuery(".vi-filer").css({"display": "none"});

            }

            if (wpProjectDOM.length === 0) {
                wpProjectDOM = '<div class="no-wpPlugin_project">'+
                        '<div class="empty-state text-center">'+
                            '<img src=' + viImageUrl + 'assets/images/vi-empty-state.svg>'+
                            '<p class="empty-heading-text">To continue,</p>'+
                            '<div class="empty-subheading-text">'+
                                '<p>1. <a href="https://chrome.google.com/webstore/detail/visual-inspector-by-canva/efaejpgmekdkcngpbghnpcmbpbngoclc" target="_blank">Download the Visual Inspector Chrome extension</a></p>'+
                                '<p>2. Make changes to your live website,</p>'+
                                '<p>3. Sync changes from Chrome extension.</p>'+
                            '</div>'+
                            '<a class="empty-vi-btn" href="https://www.canvasflip.com/visual-inspector/">Know more about Visual Inspector</a>'+
                        '</div>'+
                    '</div>';
                jQuery(".vi-project-found-title").css({"display": "none"});
            }
        }

        projectDOM += '<div data-id="' + projects[i].id + '" data-uuid="' + projects[i].uuid + '" class="vi-project col-md-4 vi-card vi-other-project-card">' +
                '<div class="vi-project-image"><div><div>' +
                '<img class="vi-project-img" src=' + viImageUrl + 'assets/images/empty.png alt="image" data-src="' + src + '"/>' +
                '</div></div></div>' +
                '<div class="vi-project-details">' +
                '<div class="vi-project-details-left">' +
                '<div class="vi-project-name">' + projects[i].url + '</div>' +
                '<div class="vi-project-total-pages">' +
                (projects[i].pages.length > 1 ? projects[i].pages.length + " Pages" : projects[i].pages.length + " Page") +
                ' </div>' +
                '</div>' +
                '</div>' +
                '</div>';
    }

    var wpDom = jQuery(wpProjectDOM).appendTo("#viWPProjectContainer");

    var dom = jQuery(projectDOM).appendTo("#viProjectsContainer");
    jQuery("#viProjectsContainer").hide();
    wpDom.find(".timeago").timeago();
    wpDom.find(".vi-project-img").each(function () {
        var thisImage = jQuery(this),
                src = thisImage.attr("data-src");

        if (src.length > 0) {
            jQuery.get(src, function (data) {
                thisImage.attr("src", data);
            });
        }
    });

    dom.find(".vi-project-img").each(function () {
        var thisImage = jQuery(this),
                src = thisImage.attr("data-src");

        if (src.length > 0) {
            jQuery.get(src, function (data) {
                thisImage.attr("src", data);
            });
        }
    });

    if (sortingProject == true) {
        showViAllProjects();
    }
}

//user clicking on sync button
jQuery("#viProjectContainer").on("click", ".vi-open-project-btn", function (event) {
    event.stopPropagation();

    var projectId = jQuery(this).parents(".vi-card").attr("data-id");
    var projectUUID = jQuery(this).parents(".vi-project").attr("data-uuid");
    jQuery(this).html("Syncing...");
    jQuery(this).attr("data-sync", "1");
    LoadProject(projectId, projectUUID);
});

jQuery("#viWPProjectLabel").on("click", function (event) {
    jQuery("#viWPProjectLabel").hide();
    jQuery(".vi-project-found-title").css({"display": "none"});
    jQuery(".vi-project-heading").hide();
    jQuery(".vi-filer").css({"display": "none"});
    jQuery("#viProjectsContainer").hide();
    jQuery("#viWPProjectContainer").show();
});

//getting css code from server
function LoadProject(projectId, projectUUID) {
    //show loading modal
    jQuery.ajax({
        type: "GET",
        url: viHost + "/vi/svcs/getProjectChanges.php",
        xhrFields: {withCredentials: true}, //to send cookies in case of inline edit
        data: {
            id: projectId,
            uuid: projectUUID,
            origin: window.location.origin,
            path: window.location.href.replace(window.location.origin, "")
        },
        success: function (data) {
            if (data.result == "success") {

                //sync changes from server
                if (data.changes != null) {
                    inspectorCssCode(data.changes);
                }
            }

        },
        fail: function (data) {
            console.log("fail");
        },
        error: function (data) {
            console.log("error");
        }
    });
}

//oraganizing css code as per css ruleset
function inspectorCssCode(viInspectCss) {
    var inspectorCssArray = [];

    //looping to get css code total array
    for (var i = 0; i < viInspectCss.length; i++) {
        //condition to remove css code array value is null
        if (viInspectCss[i] != null && viInspectCss[i].css != null) {
            //looping to get selector of the css code
            for (var j = 0; j < viInspectCss[i].css.length; j++) {
                var cssSelector = viInspectCss[i]['css'][j]['selector'];
                var cssKey = viInspectCss[i]['css'][j]['key'];
                var cssValue = viInspectCss[i]['css'][j]['value'];
                var cssKeyValue = cssKey + ":" + cssValue + ";";
                var isSelectorFound = false;

                for (var k = 0; k < inspectorCssArray.length; k++) {
                    if (cssSelector == inspectorCssArray[k].key) {
                        inspectorCssArray[k].val = inspectorCssArray[k].val + "\n\t" + cssKeyValue;
                        isSelectorFound = true;
                    }
                }

                if (!isSelectorFound) {
                    inspectorCssArray.push({
                        key: cssSelector,
                        val: "\t" + cssKeyValue
                    });
                }
            }
        }
    }

    var inspectorCssCode = [];
    for (var i = 0; i < inspectorCssArray.length; i++) {
        var inspectCss = inspectorCssArray[i].key + " {\n"
                + inspectorCssArray[i].val + "\n" +
                "}\n";
        inspectorCssCode.push(inspectCss);
    }

    inspectorCssCode = inspectorCssCode.join("");
    var cssInspect = inspectorCssCode.toString();

    updateViProjects(cssInspect);
}

//sending css code to read and write
function updateViProjects(cssCode) {
    var form = jQuery('#vi-form');
    form.find("textarea").val(cssCode);
    form.find("#vi-submit").trigger("click");
}

//user logout function
jQuery(".login-popup-btn").on("click", "#viPluginLogout", function (event) {
    jQuery.ajax({
        url: viHost + "/protected/actions/user.php?action=logout",
        xhrFields: {withCredentials: true}, //to send cookies in case of inline edit
        type: "POST",
        cache: false,
        success: function (data) {
            var loginjson = eval('(' + data + ')');

            if (loginjson["result"] === "success") {
                jQuery("#viLoginHeader").addClass("active");
                location.reload();
                renderLogin();
            }
        },
        error: function (data) {
            //..
        },
        fail: function (data) {
            //..
        }
    });
});


//Delete project
jQuery("#viProjectContainer").on("click", ".vi-project-reset", function (event) {
    event.stopPropagation();

    var projectId = jQuery(this).parents(".vi-project").attr("data-id");
    jQuery("#deleteModal").attr("data-id", projectId);
    jQuery("#deleteModal").modal('show');
});

//Cancel delete project popup
jQuery("#viDeleteCancel").on("click", function () {
    jQuery("#deleteModal").modal('hide');
});

//API to remove custom css code
jQuery("#viDeleteYes").on("click", function () {
    var form = jQuery('#vi-form');
    form.find("textarea").val("");
    form.find("#vi-submit").trigger("click");
});