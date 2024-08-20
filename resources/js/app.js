import "./bootstrap";
import "laravel-datatables-vite";
import { Malle } from "@deltablot/malle";
import pq from "pqgridf";

/*
 * jQuery plugin that changes any element on your page
 *
 * @author Big Tiger
 * @website https://github.com/bigtiger1/JQuery/
 * @license Dual licensed under the MIT or GPL Version 2 licenses
 * @version 1.0
 */
(function ($, window) {
    "use strict";

    var $win = $(window), // Reference to window
        // Reference to textarea
        $textArea = false,
        // Reference to currently edit element
        $currentlyEdited = false,
        // Some constants
        EVENT_ATTR = "data-edit-event",
        IS_EDITING_ATTR = "data-is-editing",
        DBL_TAP_EVENT = "dbltap",
        SUPPORTS_TOUCH = "ontouchend" in window,
        TINYMCE_INSTALLED =
            "tinyMCE" in window && typeof window.tinyMCE.init == "function",
        // reference to old is function
        oldjQueryIs = $.fn.is,
        /*
         * Function responsible of triggering double tap event
         */
        lastTap = 0,
        tapper = function () {
            var now = new Date().getTime();
            if (now - lastTap < 250) {
                $(this).trigger(DBL_TAP_EVENT);
            }
            lastTap = now;
        },
        /**
         * Event listener that largens font size
         */
        keyHandler = function (e) {
            if (e.keyCode == 13 && e.data.closeOnEnter) {
                $currentlyEdited.editable("close");
            } else if (
                e.data.toggleFontSize &&
                e.metaKey &&
                (e.keyCode == 38 || e.keyCode == 40)
            ) {
                var fontSize = parseInt($textArea.css("font-size"), 10);
                fontSize += e.keyCode == 40 ? -1 : 1;
                $textArea.css("font-size", fontSize + "px");
                return false;
            }
        },
        /**
         * Adjusts the height of the textarea to remove scroll
         * @todo This way of doing it does not make the textarea smaller when the number of text lines gets smaller
         */
        adjustTextAreaHeight = function () {
            if (
                $textArea[0].scrollHeight !==
                parseInt($textArea.attr("data-scroll"), 10)
            ) {
                $textArea.css("height", $textArea[0].scrollHeight + "px");
                $textArea.attr("data-scroll", $textArea[0].scrollHeight);
            }
        },
        /**
         * @param {jQuery} $el
         * @param {String} newText
         */
        resetElement = function ($el, newText) {
            $el.removeAttr("data-is-editing");
            $el.html(newText);
            $textArea.remove();
        },
        /**
         * Function creating editor
         */
        elementEditor = function ($el, opts) {
            if ($el.is(":editing")) return;

            $currentlyEdited = $el;
            $el.attr("data-is-editing", "1");

            var defaultText = $.trim($el.html()),
                defaultFontSize = $el.css("font-size"),
                elementHeight = $el.height(),
                textareaStyle =
                    "width: 96%; padding:0; margin:0; border:0; background:none;" +
                    "font-family: " +
                    $el.css("font-family") +
                    "; font-size: " +
                    $el.css("font-size") +
                    ";" +
                    "font-weight: " +
                    $el.css("font-weight") +
                    ";";

            if (opts.lineBreaks) {
                defaultText = defaultText.replace(/<br( |)(|\/)>/g, "\n");
            }

            $textArea = $("<textarea></textarea>");
            $el.text("");

            if (navigator.userAgent.match(/webkit/i) !== null) {
                textareaStyle = document.defaultView.getComputedStyle(
                    $el.get(0),
                    "",
                ).cssText;
            }

            // The editor should always be static
            textareaStyle += "position: static";

            /*
          TINYMCE EDITOR
         */
            if (opts.tinyMCE !== false) {
                var id = "editable-area-" + new Date().getTime();
                $textArea.val(defaultText).appendTo($el).attr("id", id);

                if (typeof opts.tinyMCE != "object") opts.tinyMCE = {};

                opts.tinyMCE.mode = "exact";
                opts.tinyMCE.elements = id;
                opts.tinyMCE.width = $el.innerWidth();
                opts.tinyMCE.height = $el.height() + 200;
                opts.tinyMCE.theme_advanced_resize_vertical = true;

                opts.tinyMCE.setup = function (ed) {
                    ed.onInit.add(function (editor, evt) {
                        var editorWindow = editor.getWin();
                        var hasPressedKey = false;
                        var editorBlur = function () {
                            var newText = $(editor.getDoc())
                                .find("body")
                                .html();
                            if (
                                $(newText).get(0).nodeName ==
                                $el.get(0).nodeName
                            ) {
                                newText = $(newText).html();
                            }

                            // Update element and remove editor
                            resetElement($el, newText);
                            editor.remove();
                            $textArea = false;
                            $win.unbind("click", editorBlur);
                            $currentlyEdited = false;

                            // Run callback
                            if (typeof opts.callback == "function") {
                                opts.callback({
                                    content:
                                        newText == defaultText || !hasPressedKey
                                            ? false
                                            : newText,
                                    fontSize: false,
                                    $el: $el,
                                });
                            }
                        };

                        // Blur editor when user clicks outside the editor
                        setTimeout(function () {
                            $win.bind("click", editorBlur);
                        }, 500);

                        // Create a dummy textarea that will called upon when
                        // programmatically interacting with the editor
                        $textArea = $("<textarea></textarea>");
                        $textArea.bind("blur", editorBlur);

                        editorWindow.onkeydown = function () {
                            hasPressedKey = true;
                        };

                        editorWindow.focus();
                    });
                };

                tinyMCE.init(opts.tinyMCE);
            } else {

            /*
         TEXTAREA EDITOR
         */
                if (opts.toggleFontSize || opts.closeOnEnter) {
                    $win.bind("keydown", opts, keyHandler);
                }
                $win.bind("keyup", adjustTextAreaHeight);

                $textArea
                    .val(defaultText)
                    .blur(function () {
                        $currentlyEdited = false;

                        // Get new text and font size
                        var newText = $.trim($textArea.val()),
                            newFontSize = $textArea.css("font-size");
                        if (opts.lineBreaks) {
                            newText = newText.replace(
                                new RegExp("\n", "g"),
                                "<br />",
                            );
                        }

                        // Update element
                        resetElement($el, newText);
                        if (newFontSize != defaultFontSize) {
                            $el.css("font-size", newFontSize);
                        }

                        // remove textarea and size toggles
                        $win.unbind("keydown", keyHandler);
                        $win.unbind("keyup", adjustTextAreaHeight);

                        // Run callback
                        if (typeof opts.callback == "function") {
                            opts.callback({
                                content:
                                    newText == defaultText ? false : newText,
                                fontSize:
                                    newFontSize == defaultFontSize
                                        ? false
                                        : newFontSize,
                                $el: $el,
                            });
                        }
                    })
                    .attr("style", textareaStyle)
                    .appendTo($el)
                    .css({
                        margin: 0,
                        padding: 0,
                        height: elementHeight + "px",
                        overflow: "hidden",
                    })
                    .focus()
                    .get(0)
                    .select();

                adjustTextAreaHeight();
            }

            $el.trigger("edit", [$textArea]);
        },
        /**
         * Event listener
         */
        editEvent = function (event) {
            if ($currentlyEdited !== false) {
                // Not closing the currently open editor before opening a new
                // editor makes things go crazy
                $currentlyEdited.editable("close");
                var $this = $(this);
                elementEditor($this, event.data);
            } else {
                elementEditor($(this), event.data);
            }
            return false;
        };

    /**
     * Jquery plugin that makes elments editable
     * @param {Object|String} [opts] Either callback function or the string 'destroy' if wanting to remove the editor event
     * @return {jQuery|Boolean}
     */
    $.fn.editable = function (opts) {
        if (typeof opts == "string") {
            if (this.is(":editable")) {
                switch (opts) {
                    case "open":
                        if (!this.is(":editing")) {
                            this.trigger(this.attr(EVENT_ATTR));
                        }
                        break;
                    case "close":
                        if (this.is(":editing")) {
                            $textArea.trigger("blur");
                        }
                        break;
                    case "destroy":
                        if (this.is(":editing")) {
                            $textArea.trigger("blur");
                        }
                        this.unbind(this.attr(EVENT_ATTR));
                        this.removeAttr(EVENT_ATTR);
                        break;
                    default:
                        console.warn(
                            'Unknown command "' +
                                opts +
                                '" for jquery.editable',
                        );
                }
            } else {
                console.error(
                    "Calling .editable() on an element that is not editable, call .editable() first",
                );
            }
        } else {
            if (this.is(":editable")) {
                console.warn(
                    'Making an already editable element editable, call .editable("destroy") first',
                );
                this.editable("destroy");
            }

            opts = $.extend(
                {
                    event: "dblclick",
                    touch: true,
                    lineBreaks: true,
                    toggleFontSize: true,
                    closeOnEnter: false,
                    tinyMCE: false,
                },
                opts,
            );

            if (opts.tinyMCE !== false && !TINYMCE_INSTALLED) {
                console.warn(
                    "Trying to use tinyMCE as editor but id does not seem to be installed",
                );
                opts.tinyMCE = false;
            }

            if (SUPPORTS_TOUCH && opts.touch) {
                opts.event = DBL_TAP_EVENT;
                this.unbind("touchend", tapper);
                this.bind("touchend", tapper);
            } else {
                opts.event += ".textEditor";
            }

            this.bind(opts.event, opts, editEvent);
            this.attr(EVENT_ATTR, opts.event);
        }

        return this;
    };

    /**
     * Add :editable :editing to $.is()
     * @param {Object} statement
     * @return {*}
     */
    $.fn.is = function (statement) {
        if (typeof statement == "string" && statement.indexOf(":") === 0) {
            if (statement == ":editable") {
                return this.attr(EVENT_ATTR) !== undefined;
            } else if (statement == ":editing") {
                return this.attr(IS_EDITING_ATTR) !== undefined;
            }
        }
        return oldjQueryIs.apply(this, arguments);
    };
})(jQuery, window);

(function ($) {
    var editorId = "dom-edit-" + Date.now();
    var editorHTML = '<textarea id="' + editorId + '"></textarea>';
    var $editor = $(editorHTML);
    var $currentTargetElement = null;

    function preventDefaultEvents(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function getTargetElementBoundingRect($aTargetElement) {
        var offset = $aTargetElement.offset();
        return {
            left: offset.left,
            top: offset.top,
            width: $aTargetElement.width(),
            height: $aTargetElement.height(),
        };
    }

    function closeDomEditor(e) {
        $editor.remove();

        if ($currentTargetElement) {
            $currentTargetElement.html($editor.val());
        }

        $currentTargetElement = null;
        //$(document).off('click', closeDomEditor);
    }

    function editorClick(e) {
        preventDefaultEvents(e);
    }

    function setEditorStyle($element, opts) {
        $editor.css(getTargetElementBoundingRect($element));
        $editor.css("font-size", $element.css("font-size"));
        $editor.css("font-weight", $element.css("font-weight"));
        $editor.css("text-align", $element.css("text-align"));
        $editor.css("font-family", $element.css("font-family"));
        $editor.css("padding", $element.css("padding"));
        $editor.css("position", "absolute");

        if (opts && opts.onSetEditorStyle) {
            opts.onSetEditorStyle($editor, $element);
        }
    }

    function setEditorState($element) {
        $editor.val($element.html());
        $editor.select();
        $editor.focus();
        $editor.click(editorClick);
        $editor.blur(closeDomEditor);
    }

    $.fn.domEdit = function (options) {
        var defaultOptions = {
            editorClass: "",
        };

        var opts = $.extend(defaultOptions, options);
        $editor.addClass(opts.editorClass);

        return this.each(function (idx, element) {
            $(element).dblclick(function (e) {
                preventDefaultEvents(e);
                var target = e.target;
                var $body = $(document.body);

                if (
                    target === $editor[0] ||
                    target === document.body ||
                    !$body.has(target)
                )
                    return;

                var $element = $(target);

                if (!$editor.parent().length) {
                    $body.append($editor);
                }

                setEditorStyle($element, opts);
                setEditorState($element);
                //$(document).on('click', closeDomEditor);
                $currentTargetElement = $element;
            });
        });
    };
})(jQuery);

document.addEventListener("DOMContentLoaded", function () {
    // window.Echo.channel('movie-revenue')
    //   .listen('salesSearchEvent', (e) => {
    //     debugger;
    //     console.log(e);
    // });

    window.Echo.private("movie-revenue").listen("salesSearchEvent", (e) => {
        console.log(e);
    });

    window.Echo.channel("qr-channel").listen("QRScanned", (e) => {
        console.log("here");

        // Assuming 'e' contains the ticket information in a structure like { movie: 'Movie Name', movietime: 'Time', location: 'Location', hall: 'Hall' }
        const table = document.getElementById("ticketsTable"); // Select the table by ID
        const tbody = table.getElementsByTagName("tbody")[0]; // Get the tbody element of the table
        const newRow = tbody.insertRow(); // Insert a new row at the end of the table

        // Insert new cells (<td>) at the 0th, 1st, 2nd, and 3rd position of the "newRow"
        const movieCell = newRow.insertCell(0);
        const timeCell = newRow.insertCell(1);
        const locationCell = newRow.insertCell(2);
        const hallCell = newRow.insertCell(3);

        // Add the text content for each cell
        movieCell.textContent = e.movie_name;
        timeCell.textContent = e.movie_time;
        locationCell.textContent = e.location;
        hallCell.textContent = e.hall;

        // Optionally, add classes or styles to the new row or cells
        newRow.className =
            "bg-white border-b dark:bg-gray-800 dark:border-gray-700";
        movieCell.className =
            "px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white";
        timeCell.className = "px-6 py-4";
        locationCell.className = "px-6 py-4";
        hallCell.className = "px-6 py-4";
    });

    window.Echo.channel("test").listen("ChatSent", (event) => {
        console.log("xb");

        const id = document.querySelector("#userid").value;
        console.log(id);
        console.log(event.userid, "event");
        if (id == event.userid) {
            if (event.msginstance.type === "admin") {
                let el = document.querySelector(".direct-chat-messages");
                let newMessage = document.createElement("div");
                newMessage.classList.add("direct-chat-msg");

                let chatInfos = document.createElement("div");
                chatInfos.classList.add("direct-chat-infos", "clearfix");

                let chatName = document.createElement("span");
                chatName.classList.add("direct-chat-name", "float-left");
                chatName.textContent = "Admin";

                let chatText = document.createElement("div");
                chatText.classList.add("direct-chat-text");

                let chatMessage = document.createElement("p");
                chatMessage.textContent = event.validated.message;

                // Append elements together
                chatInfos.appendChild(chatName);
                chatText.appendChild(chatMessage);
                newMessage.appendChild(chatInfos);
                newMessage.appendChild(chatText);

                // Append the new message to the chat messages container
                console.log(newMessage);

                el.appendChild(newMessage);
            } else if (event.msginstance.type === "user") {
                let el = document.querySelector(".direct-chat-messages");
                let newMessage = document.createElement("div");
                newMessage.classList.add("direct-chat-msg", "right");

                let chatInfos = document.createElement("div");
                chatInfos.classList.add("direct-chat-infos", "clearfix");

                let chatName = document.createElement("span");
                chatName.classList.add("direct-chat-name", "float-right");
                chatName.textContent = event.username;

                let chatText = document.createElement("div");
                chatText.classList.add("direct-chat-text");

                let chatMessage = document.createElement("p");
                chatMessage.textContent = event.validated.message;

                // Append elements together
                chatInfos.appendChild(chatName);
                chatText.appendChild(chatMessage);
                newMessage.appendChild(chatInfos);
                newMessage.appendChild(chatText);

                // Append the new message to the chat messages container
                el.appendChild(newMessage);
                console.log(newMessage);
            }
        }
    });

    const malle = new Malle({
        // this is the function that will be called once user has finished entering text (press Enter or click outside)
        // it receives the new value, the original element, the event and the input element
        fun: (value, original, event, input) => {
            console.log(`New text: ${value}`);
            console.log(`Original element:`);
            console.log(original);
            // add here your code for POSTing the new value
            return myFunctionReturingAPromiseString();
        },
    }).listen(); // directly start listening after object creation
});
