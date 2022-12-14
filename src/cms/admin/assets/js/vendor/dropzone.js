// ##############################################################################
// FILE: Vendor/DropZone/dropzone.js
// ##############################################################################
/*
(The MIT License)

Copyright (c) 2012 Matias Meno <m@tias.me>
Logo & Website Design (c) 2015 "1910" www.weare1910.com

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
! function() {
    function a(b) {
        var c = a.modules[b];
        if (!c) throw new Error('failed to require "' + b + '"');
        return "exports" in c || "function" != typeof c.definition || (c.client = c.component = !0, c.definition.call(this, c.exports = {}, c), delete c.definition), c.exports
    }
    a.modules = {}, a.register = function(b, c) {
        a.modules[b] = {
            definition: c
        }
    }, a.define = function(b, c) {
        a.modules[b] = {
            exports: c
        }
    }, a.register("dropzone", function(b, c) {
        c.exports = a("dropzone/lib/dropzone.js")
    }), a.register("dropzone/lib/dropzone.js", function(a, b) {
        (function() {
            var a, c, d, e, f, g, h, i, j = [].slice,
                k = {}.hasOwnProperty,
                l = function(a, b) {
                    function c() {
                        this.constructor = a
                    }
                    for (var d in b) k.call(b, d) && (a[d] = b[d]);
                    return c.prototype = b.prototype, a.prototype = new c, a.__super__ = b.prototype, a
                };
            h = function() {}, c = function() {
                function a() {}
                return a.prototype.addEventListener = a.prototype.on, a.prototype.on = function(a, b) {
                    return this._callbacks = this._callbacks || {}, this._callbacks[a] || (this._callbacks[a] = []), this._callbacks[a].push(b), this
                }, a.prototype.emit = function() {
                    var a, b, c, d, e, f;
                    if (d = arguments[0], a = 2 <= arguments.length ? j.call(arguments, 1) : [], this._callbacks = this._callbacks || {}, c = this._callbacks[d])
                        for (e = 0, f = c.length; f > e; e++) b = c[e], b.apply(this, a);
                    return this
                }, a.prototype.removeListener = a.prototype.off, a.prototype.removeAllListeners = a.prototype.off, a.prototype.removeEventListener = a.prototype.off, a.prototype.off = function(a, b) {
                    var c, d, e, f, g;
                    if (!this._callbacks || 0 === arguments.length) return this._callbacks = {}, this;
                    if (d = this._callbacks[a], !d) return this;
                    if (1 === arguments.length) return delete this._callbacks[a], this;
                    for (e = f = 0, g = d.length; g > f; e = ++f)
                        if (c = d[e], c === b) {
                            d.splice(e, 1);
                            break
                        }
                    return this
                }, a
            }(), a = function(a) {
                function b(a, c) {
                    var e, f, g;
                    if (this.element = a, this.version = b.version, this.defaultOptions.previewTemplate = this.defaultOptions.previewTemplate.replace(/\n*/g, ""), this.clickableElements = [], this.listeners = [], this.files = [], "string" == typeof this.element && (this.element = document.querySelector(this.element)), !this.element || null == this.element.nodeType) throw new Error("Invalid dropzone element.");
                    if (this.element.dropzone) throw new Error("Dropzone already attached.");
                    if (b.instances.push(this), this.element.dropzone = this, e = null != (g = b.optionsForElement(this.element)) ? g : {}, this.options = d({}, this.defaultOptions, e, null != c ? c : {}), this.options.forceFallback || !b.isBrowserSupported()) return this.options.fallback.call(this);
                    if (null == this.options.url && (this.options.url = this.element.getAttribute("action")), !this.options.url) throw new Error("No URL provided.");
                    if (this.options.acceptedFiles && this.options.acceptedMimeTypes) throw new Error("You can't provide both 'acceptedFiles' and 'acceptedMimeTypes'. 'acceptedMimeTypes' is deprecated.");
                    this.options.acceptedMimeTypes && (this.options.acceptedFiles = this.options.acceptedMimeTypes, delete this.options.acceptedMimeTypes), this.options.method = this.options.method.toUpperCase(), (f = this.getExistingFallback()) && f.parentNode && f.parentNode.removeChild(f), this.options.previewsContainer !== !1 && (this.previewsContainer = this.options.previewsContainer ? b.getElement(this.options.previewsContainer, "previewsContainer") : this.element), this.options.clickable && (this.clickableElements = this.options.clickable === !0 ? [this.element] : b.getElements(this.options.clickable, "clickable")), this.init()
                }
                var d, e;
                return l(b, a), b.prototype.Emitter = c, b.prototype.events = ["drop", "dragstart", "dragend", "dragenter", "dragover", "dragleave", "addedfile", "removedfile", "thumbnail", "error", "errormultiple", "processing", "processingmultiple", "uploadprogress", "totaluploadprogress", "sending", "sendingmultiple", "success", "successmultiple", "canceled", "canceledmultiple", "complete", "completemultiple", "reset", "maxfilesexceeded", "maxfilesreached", "queuecomplete"], b.prototype.defaultOptions = {
                    url: null,
                    method: "post",
                    withCredentials: !1,
                    parallelUploads: 2,
                    uploadMultiple: !1,
                    maxFilesize: 256,
                    paramName: "file",
                    createImageThumbnails: !0,
                    maxThumbnailFilesize: 10,
                    thumbnailWidth: 100,
                    thumbnailHeight: 100,
                    maxFiles: null,
                    params: {},
                    clickable: !0,
                    ignoreHiddenFiles: !0,
                    acceptedFiles: null,
                    acceptedMimeTypes: null,
                    autoProcessQueue: !0,
                    autoQueue: !0,
                    addRemoveLinks: !1,
                    previewsContainer: null,
                    capture: null,
                    dictDefaultMessage: "Drop files here to upload",
                    dictFallbackMessage: "Your browser does not support drag'n'drop file uploads.",
                    dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
                    dictFileTooBig: "File is too big ({{filesize}}MiB). Max filesize: {{maxFilesize}}MiB.",
                    dictInvalidFileType: "You can't upload files of this type.",
                    dictResponseError: "Server responded with {{statusCode}} code.",
                    dictCancelUpload: "Cancel upload",
                    dictCancelUploadConfirmation: "Are you sure you want to cancel this upload?",
                    dictRemoveFile: "Remove file",
                    dictRemoveFileConfirmation: null,
                    dictMaxFilesExceeded: "You can not upload any more files.",
                    accept: function(a, b) {
                        return b()
                    },
                    init: function() {
                        return h
                    },
                    forceFallback: !1,
                    fallback: function() {
                        var a, c, d, e, f, g;
                        for (this.element.className = "" + this.element.className + " dz-browser-not-supported", g = this.element.getElementsByTagName("div"), e = 0, f = g.length; f > e; e++) a = g[e], /(^| )dz-message($| )/.test(a.className) && (c = a, a.className = "dz-message");
                        return c || (c = b.createElement('<div class="dz-message"><span></span></div>'), this.element.appendChild(c)), d = c.getElementsByTagName("span")[0], d && (d.textContent = this.options.dictFallbackMessage), this.element.appendChild(this.getFallbackForm())
                    },
                    resize: function(a) {
                        var b, c, d;
                        return b = {
                            srcX: 0,
                            srcY: 0,
                            srcWidth: a.width,
                            srcHeight: a.height
                        }, c = a.width / a.height, b.optWidth = this.options.thumbnailWidth, b.optHeight = this.options.thumbnailHeight, null == b.optWidth && null == b.optHeight ? (b.optWidth = b.srcWidth, b.optHeight = b.srcHeight) : null == b.optWidth ? b.optWidth = c * b.optHeight : null == b.optHeight && (b.optHeight = 1 / c * b.optWidth), d = b.optWidth / b.optHeight, a.height < b.optHeight || a.width < b.optWidth ? (b.trgHeight = b.srcHeight, b.trgWidth = b.srcWidth) : c > d ? (b.srcHeight = a.height, b.srcWidth = b.srcHeight * d) : (b.srcWidth = a.width, b.srcHeight = b.srcWidth / d), b.srcX = (a.width - b.srcWidth) / 2, b.srcY = (a.height - b.srcHeight) / 2, b
                    },
                    drop: function() {
                        return this.element.classList.remove("dz-drag-hover")
                    },
                    dragstart: h,
                    dragend: function() {
                        return this.element.classList.remove("dz-drag-hover")
                    },
                    dragenter: function() {
                        return this.element.classList.add("dz-drag-hover")
                    },
                    dragover: function() {
                        return this.element.classList.add("dz-drag-hover")
                    },
                    dragleave: function() {
                        return this.element.classList.remove("dz-drag-hover")
                    },
                    paste: h,
                    reset: function() {
                        return this.element.classList.remove("dz-started")
                    },
                    addedfile: function(a) {
                        var c, d, e, f, g, h, i, j, k, l, m, n, o;
                        if (this.element === this.previewsContainer && this.element.classList.add("dz-started"), this.previewsContainer) {
                            for (a.previewElement = b.createElement(this.options.previewTemplate.trim()), a.previewTemplate = a.previewElement, this.previewsContainer.appendChild(a.previewElement), l = a.previewElement.querySelectorAll("[data-dz-name]"), f = 0, i = l.length; i > f; f++) c = l[f], c.textContent = a.name;
                            for (m = a.previewElement.querySelectorAll("[data-dz-size]"), g = 0, j = m.length; j > g; g++) c = m[g], c.innerHTML = this.filesize(a.size);
                            for (this.options.addRemoveLinks && (a._removeLink = b.createElement('<a class="dz-remove" href="javascript:undefined;" data-dz-remove>' + this.options.dictRemoveFile + "</a>"), a.previewElement.appendChild(a._removeLink)), d = function(c) {
                                    return function(d) {
                                        return d.preventDefault(), d.stopPropagation(), a.status === b.UPLOADING ? b.confirm(c.options.dictCancelUploadConfirmation, function() {
                                            return c.removeFile(a)
                                        }) : c.options.dictRemoveFileConfirmation ? b.confirm(c.options.dictRemoveFileConfirmation, function() {
                                            return c.removeFile(a)
                                        }) : c.removeFile(a)
                                    }
                                }(this), n = a.previewElement.querySelectorAll("[data-dz-remove]"), o = [], h = 0, k = n.length; k > h; h++) e = n[h], o.push(e.addEventListener("click", d));
                            return o
                        }
                    },
                    removedfile: function(a) {
                        var b;
                        return a.previewElement && null != (b = a.previewElement) && b.parentNode.removeChild(a.previewElement), this._updateMaxFilesReachedClass()
                    },
                    thumbnail: function(a, b) {
                        var c, d, e, f, g;
                        if (a.previewElement) {
                            for (a.previewElement.classList.remove("dz-file-preview"), a.previewElement.classList.add("dz-image-preview"), f = a.previewElement.querySelectorAll("[data-dz-thumbnail]"), g = [], d = 0, e = f.length; e > d; d++) c = f[d], c.alt = a.name, g.push(c.src = b);
                            return g
                        }
                    },
                    error: function(a, b) {
                        var c, d, e, f, g;
                        if (a.previewElement) {
                            for (a.previewElement.classList.add("dz-error"), "String" != typeof b && b.error && (b = b.error), f = a.previewElement.querySelectorAll("[data-dz-errormessage]"), g = [], d = 0, e = f.length; e > d; d++) c = f[d], g.push(c.textContent = b);
                            return g
                        }
                    },
                    errormultiple: h,
                    processing: function(a) {
                        return a.previewElement && (a.previewElement.classList.add("dz-processing"), a._removeLink) ? a._removeLink.textContent = this.options.dictCancelUpload : void 0
                    },
                    processingmultiple: h,
                    uploadprogress: function(a, b) {
                        var c, d, e, f, g;
                        if (a.previewElement) {
                            for (f = a.previewElement.querySelectorAll("[data-dz-uploadprogress]"), g = [], d = 0, e = f.length; e > d; d++) c = f[d], g.push("PROGRESS" === c.nodeName ? c.value = b : c.style.width = "" + b + "%");
                            return g
                        }
                    },
                    totaluploadprogress: h,
                    sending: h,
                    sendingmultiple: h,
                    success: function(a) {
                        return a.previewElement ? a.previewElement.classList.add("dz-success") : void 0
                    },
                    successmultiple: h,
                    canceled: function(a) {
                        return this.emit("error", a, "Upload canceled.")
                    },
                    canceledmultiple: h,
                    complete: function(a) {
                        return a._removeLink ? a._removeLink.textContent = this.options.dictRemoveFile : void 0
                    },
                    completemultiple: h,
                    maxfilesexceeded: h,
                    maxfilesreached: h,
                    queuecomplete: h,
                    previewTemplate: '<div class="dz-preview dz-file-preview">\n  <div class="dz-details">\n    <div class="dz-filename"><span data-dz-name></span></div>\n    <div class="dz-size" data-dz-size></div>\n    <img data-dz-thumbnail />\n  </div>\n  <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>\n  <div class="dz-success-mark"><span>???</span></div>\n  <div class="dz-error-mark"><span>???</span></div>\n  <div class="dz-error-message"><span data-dz-errormessage></span></div>\n</div>'
                }, d = function() {
                    var a, b, c, d, e, f, g;
                    for (d = arguments[0], c = 2 <= arguments.length ? j.call(arguments, 1) : [], f = 0, g = c.length; g > f; f++) {
                        b = c[f];
                        for (a in b) e = b[a], d[a] = e
                    }
                    return d
                }, b.prototype.getAcceptedFiles = function() {
                    var a, b, c, d, e;
                    for (d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b], a.accepted && e.push(a);
                    return e
                }, b.prototype.getRejectedFiles = function() {
                    var a, b, c, d, e;
                    for (d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b], a.accepted || e.push(a);
                    return e
                }, b.prototype.getFilesWithStatus = function(a) {
                    var b, c, d, e, f;
                    for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) b = e[c], b.status === a && f.push(b);
                    return f
                }, b.prototype.getQueuedFiles = function() {
                    return this.getFilesWithStatus(b.QUEUED)
                }, b.prototype.getUploadingFiles = function() {
                    return this.getFilesWithStatus(b.UPLOADING)
                }, b.prototype.getActiveFiles = function() {
                    var a, c, d, e, f;
                    for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) a = e[c], (a.status === b.UPLOADING || a.status === b.QUEUED) && f.push(a);
                    return f
                }, b.prototype.init = function() {
                    var a, c, d, e, f, g, h;
                    for ("form" === this.element.tagName && this.element.setAttribute("enctype", "multipart/form-data"), this.element.classList.contains("dropzone") && !this.element.querySelector(".dz-message") && this.element.appendChild(b.createElement('<div class="dz-default dz-message"><span>' + this.options.dictDefaultMessage + "</span></div>")), this.clickableElements.length && (d = function(a) {
                            return function() {
                                return a.hiddenFileInput && document.body.removeChild(a.hiddenFileInput), a.hiddenFileInput = document.createElement("input"), a.hiddenFileInput.setAttribute("type", "file"), (null == a.options.maxFiles || a.options.maxFiles > 1) && a.hiddenFileInput.setAttribute("multiple", "multiple"), a.hiddenFileInput.className = "dz-hidden-input", null != a.options.acceptedFiles && a.hiddenFileInput.setAttribute("accept", a.options.acceptedFiles), null != a.options.capture && a.hiddenFileInput.setAttribute("capture", a.options.capture), a.hiddenFileInput.style.visibility = "hidden", a.hiddenFileInput.style.position = "absolute", a.hiddenFileInput.style.top = "0", a.hiddenFileInput.style.left = "0", a.hiddenFileInput.style.height = "0", a.hiddenFileInput.style.width = "0", document.body.appendChild(a.hiddenFileInput), a.hiddenFileInput.addEventListener("change", function() {
                                    var b, c, e, f;
                                    if (c = a.hiddenFileInput.files, c.length)
                                        for (e = 0, f = c.length; f > e; e++) b = c[e], a.addFile(b);
                                    return d()
                                })
                            }
                        }(this))(), this.URL = null != (g = window.URL) ? g : window.webkitURL, h = this.events, e = 0, f = h.length; f > e; e++) a = h[e], this.on(a, this.options[a]);
                    return this.on("uploadprogress", function(a) {
                        return function() {
                            return a.updateTotalUploadProgress()
                        }
                    }(this)), this.on("removedfile", function(a) {
                        return function() {
                            return a.updateTotalUploadProgress()
                        }
                    }(this)), this.on("canceled", function(a) {
                        return function(b) {
                            return a.emit("complete", b)
                        }
                    }(this)), this.on("complete", function(a) {
                        return function() {
                            return 0 === a.getUploadingFiles().length && 0 === a.getQueuedFiles().length ? setTimeout(function() {
                                return a.emit("queuecomplete")
                            }, 0) : void 0
                        }
                    }(this)), c = function(a) {
                        return a.stopPropagation(), a.preventDefault ? a.preventDefault() : a.returnValue = !1
                    }, this.listeners = [{
                        element: this.element,
                        events: {
                            dragstart: function(a) {
                                return function(b) {
                                    return a.emit("dragstart", b)
                                }
                            }(this),
                            dragenter: function(a) {
                                return function(b) {
                                    return c(b), a.emit("dragenter", b)
                                }
                            }(this),
                            dragover: function(a) {
                                return function(b) {
                                    var d;
                                    try {
                                        d = b.dataTransfer.effectAllowed
                                    } catch (e) {}
                                    return b.dataTransfer.dropEffect = "move" === d || "linkMove" === d ? "move" : "copy", c(b), a.emit("dragover", b)
                                }
                            }(this),
                            dragleave: function(a) {
                                return function(b) {
                                    return a.emit("dragleave", b)
                                }
                            }(this),
                            drop: function(a) {
                                return function(b) {
                                    return c(b), a.drop(b)
                                }
                            }(this),
                            dragend: function(a) {
                                return function(b) {
                                    return a.emit("dragend", b)
                                }
                            }(this)
                        }
                    }], this.clickableElements.forEach(function(a) {
                        return function(c) {
                            return a.listeners.push({
                                element: c,
                                events: {
                                    click: function(d) {
                                        return c !== a.element || d.target === a.element || b.elementInside(d.target, a.element.querySelector(".dz-message")) ? a.hiddenFileInput.click() : void 0
                                    }
                                }
                            })
                        }
                    }(this)), this.enable(), this.options.init.call(this)
                }, b.prototype.destroy = function() {
                    var a;
                    return this.disable(), this.removeAllFiles(!0), (null != (a = this.hiddenFileInput) ? a.parentNode : void 0) && (this.hiddenFileInput.parentNode.removeChild(this.hiddenFileInput), this.hiddenFileInput = null), delete this.element.dropzone, b.instances.splice(b.instances.indexOf(this), 1)
                }, b.prototype.updateTotalUploadProgress = function() {
                    var a, b, c, d, e, f, g, h;
                    if (d = 0, c = 0, a = this.getActiveFiles(), a.length) {
                        for (h = this.getActiveFiles(), f = 0, g = h.length; g > f; f++) b = h[f], d += b.upload.bytesSent, c += b.upload.total;
                        e = 100 * d / c
                    } else e = 100;
                    return this.emit("totaluploadprogress", e, c, d)
                }, b.prototype._getParamName = function(a) {
                    return "function" == typeof this.options.paramName ? this.options.paramName(a) : "" + this.options.paramName + (this.options.uploadMultiple ? "[" + a + "]" : "")
                }, b.prototype.getFallbackForm = function() {
                    var a, c, d, e;
                    return (a = this.getExistingFallback()) ? a : (d = '<div class="dz-fallback">', this.options.dictFallbackText && (d += "<p>" + this.options.dictFallbackText + "</p>"), d += '<input type="file" name="' + this._getParamName(0) + '" ' + (this.options.uploadMultiple ? 'multiple="multiple"' : void 0) + ' /><input type="submit" value="Upload!"></div>', c = b.createElement(d), "FORM" !== this.element.tagName ? (e = b.createElement('<form action="' + this.options.url + '" enctype="multipart/form-data" method="' + this.options.method + '"></form>'), e.appendChild(c)) : (this.element.setAttribute("enctype", "multipart/form-data"), this.element.setAttribute("method", this.options.method)), null != e ? e : c)
                }, b.prototype.getExistingFallback = function() {
                    var a, b, c, d, e, f;
                    for (b = function(a) {
                            var b, c, d;
                            for (c = 0, d = a.length; d > c; c++)
                                if (b = a[c], /(^| )fallback($| )/.test(b.className)) return b
                        }, f = ["div", "form"], d = 0, e = f.length; e > d; d++)
                        if (c = f[d], a = b(this.element.getElementsByTagName(c))) return a
                }, b.prototype.setupEventListeners = function() {
                    var a, b, c, d, e, f, g;
                    for (f = this.listeners, g = [], d = 0, e = f.length; e > d; d++) a = f[d], g.push(function() {
                        var d, e;
                        d = a.events, e = [];
                        for (b in d) c = d[b], e.push(a.element.addEventListener(b, c, !1));
                        return e
                    }());
                    return g
                }, b.prototype.removeEventListeners = function() {
                    var a, b, c, d, e, f, g;
                    for (f = this.listeners, g = [], d = 0, e = f.length; e > d; d++) a = f[d], g.push(function() {
                        var d, e;
                        d = a.events, e = [];
                        for (b in d) c = d[b], e.push(a.element.removeEventListener(b, c, !1));
                        return e
                    }());
                    return g
                }, b.prototype.disable = function() {
                    var a, b, c, d, e;
                    for (this.clickableElements.forEach(function(a) {
                            return a.classList.remove("dz-clickable")
                        }), this.removeEventListeners(), d = this.files, e = [], b = 0, c = d.length; c > b; b++) a = d[b], e.push(this.cancelUpload(a));
                    return e
                }, b.prototype.enable = function() {
                    return this.clickableElements.forEach(function(a) {
                        return a.classList.add("dz-clickable")
                    }), this.setupEventListeners()
                }, b.prototype.filesize = function(a) {
                    var b;
                    return a >= 109951162777.6 ? (a /= 109951162777.6, b = "TiB") : a >= 107374182.4 ? (a /= 107374182.4, b = "GiB") : a >= 104857.6 ? (a /= 104857.6, b = "MiB") : a >= 102.4 ? (a /= 102.4, b = "KiB") : (a = 10 * a, b = "b"), "<strong>" + Math.round(a) / 10 + "</strong> " + b
                }, b.prototype._updateMaxFilesReachedClass = function() {
                    return null != this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (this.getAcceptedFiles().length === this.options.maxFiles && this.emit("maxfilesreached", this.files), this.element.classList.add("dz-max-files-reached")) : this.element.classList.remove("dz-max-files-reached")
                }, b.prototype.drop = function(a) {
                    var b, c;
                    a.dataTransfer && (this.emit("drop", a), b = a.dataTransfer.files, b.length && (c = a.dataTransfer.items, c && c.length && null != c[0].webkitGetAsEntry ? this._addFilesFromItems(c) : this.handleFiles(b)))
                }, b.prototype.paste = function(a) {
                    var b, c;
                    if (null != (null != a && null != (c = a.clipboardData) ? c.items : void 0)) return this.emit("paste", a), b = a.clipboardData.items, b.length ? this._addFilesFromItems(b) : void 0
                }, b.prototype.handleFiles = function(a) {
                    var b, c, d, e;
                    for (e = [], c = 0, d = a.length; d > c; c++) b = a[c], e.push(this.addFile(b));
                    return e
                }, b.prototype._addFilesFromItems = function(a) {
                    var b, c, d, e, f;
                    for (f = [], d = 0, e = a.length; e > d; d++) c = a[d], f.push(null != c.webkitGetAsEntry && (b = c.webkitGetAsEntry()) ? b.isFile ? this.addFile(c.getAsFile()) : b.isDirectory ? this._addFilesFromDirectory(b, b.name) : void 0 : null != c.getAsFile ? null == c.kind || "file" === c.kind ? this.addFile(c.getAsFile()) : void 0 : void 0);
                    return f
                }, b.prototype._addFilesFromDirectory = function(a, b) {
                    var c, d;
                    return c = a.createReader(), d = function(a) {
                        return function(c) {
                            var d, e, f;
                            for (e = 0, f = c.length; f > e; e++) d = c[e], d.isFile ? d.file(function(c) {
                                return a.options.ignoreHiddenFiles && "." === c.name.substring(0, 1) ? void 0 : (c.fullPath = "" + b + "/" + c.name, a.addFile(c))
                            }) : d.isDirectory && a._addFilesFromDirectory(d, "" + b + "/" + d.name)
                        }
                    }(this), c.readEntries(d, function(a) {
                        return "undefined" != typeof console && null !== console && "function" == typeof console.log ? console.log(a) : void 0
                    })
                }, b.prototype.accept = function(a, c) {
                    return a.size > 1024 * this.options.maxFilesize * 1024 ? c(this.options.dictFileTooBig.replace("{{filesize}}", Math.round(a.size / 1024 / 10.24) / 100).replace("{{maxFilesize}}", this.options.maxFilesize)) : b.isValidFile(a, this.options.acceptedFiles) ? null != this.options.maxFiles && this.getAcceptedFiles().length >= this.options.maxFiles ? (c(this.options.dictMaxFilesExceeded.replace("{{maxFiles}}", this.options.maxFiles)), this.emit("maxfilesexceeded", a)) : this.options.accept.call(this, a, c) : c(this.options.dictInvalidFileType)
                }, b.prototype.addFile = function(a) {
                    return a.upload = {
                        progress: 0,
                        total: a.size,
                        bytesSent: 0
                    }, this.files.push(a), a.status = b.ADDED, this.emit("addedfile", a), this._enqueueThumbnail(a), this.accept(a, function(b) {
                        return function(c) {
                            return c ? (a.accepted = !1, b._errorProcessing([a], c)) : (a.accepted = !0, b.options.autoQueue && b.enqueueFile(a)), b._updateMaxFilesReachedClass()
                        }
                    }(this))
                }, b.prototype.enqueueFiles = function(a) {
                    var b, c, d;
                    for (c = 0, d = a.length; d > c; c++) b = a[c], this.enqueueFile(b);
                    return null
                }, b.prototype.enqueueFile = function(a) {
                    if (a.status !== b.ADDED || a.accepted !== !0) throw new Error("This file can't be queued because it has already been processed or was rejected.");
                    return a.status = b.QUEUED, this.options.autoProcessQueue ? setTimeout(function(a) {
                        return function() {
                            return a.processQueue()
                        }
                    }(this), 0) : void 0
                }, b.prototype._thumbnailQueue = [], b.prototype._processingThumbnail = !1, b.prototype._enqueueThumbnail = function(a) {
                    return this.options.createImageThumbnails && a.type.match(/image.*/) && a.size <= 1024 * this.options.maxThumbnailFilesize * 1024 ? (this._thumbnailQueue.push(a), setTimeout(function(a) {
                        return function() {
                            return a._processThumbnailQueue()
                        }
                    }(this), 0)) : void 0
                }, b.prototype._processThumbnailQueue = function() {
                    return this._processingThumbnail || 0 === this._thumbnailQueue.length ? void 0 : (this._processingThumbnail = !0, this.createThumbnail(this._thumbnailQueue.shift(), function(a) {
                        return function() {
                            return a._processingThumbnail = !1, a._processThumbnailQueue()
                        }
                    }(this)))
                }, b.prototype.removeFile = function(a) {
                    return a.status === b.UPLOADING && this.cancelUpload(a), this.files = i(this.files, a), this.emit("removedfile", a), 0 === this.files.length ? this.emit("reset") : void 0
                }, b.prototype.removeAllFiles = function(a) {
                    var c, d, e, f;
                    for (null == a && (a = !1), f = this.files.slice(), d = 0, e = f.length; e > d; d++) c = f[d], (c.status !== b.UPLOADING || a) && this.removeFile(c);
                    return null
                }, b.prototype.createThumbnail = function(a, b) {
                    var c;
                    return c = new FileReader, c.onload = function(d) {
                        return function() {
                            var e;
                            return "image/svg+xml" === a.type ? (d.emit("thumbnail", a, c.result), void(null != b && b())) : (e = document.createElement("img"), e.onload = function() {
                                var c, f, h, i, j, k, l, m;
                                return a.width = e.width, a.height = e.height, h = d.options.resize.call(d, a), null == h.trgWidth && (h.trgWidth = h.optWidth), null == h.trgHeight && (h.trgHeight = h.optHeight), c = document.createElement("canvas"), f = c.getContext("2d"), c.width = h.trgWidth, c.height = h.trgHeight, g(f, e, null != (j = h.srcX) ? j : 0, null != (k = h.srcY) ? k : 0, h.srcWidth, h.srcHeight, null != (l = h.trgX) ? l : 0, null != (m = h.trgY) ? m : 0, h.trgWidth, h.trgHeight), i = c.toDataURL("image/png"), d.emit("thumbnail", a, i), null != b ? b() : void 0
                            }, e.src = c.result)
                        }
                    }(this), c.readAsDataURL(a)
                }, b.prototype.processQueue = function() {
                    var a, b, c, d;
                    if (b = this.options.parallelUploads, c = this.getUploadingFiles().length, a = c, !(c >= b) && (d = this.getQueuedFiles(), d.length > 0)) {
                        if (this.options.uploadMultiple) return this.processFiles(d.slice(0, b - c));
                        for (; b > a;) {
                            if (!d.length) return;
                            this.processFile(d.shift()), a++
                        }
                    }
                }, b.prototype.processFile = function(a) {
                    return this.processFiles([a])
                }, b.prototype.processFiles = function(a) {
                    var c, d, e;
                    for (d = 0, e = a.length; e > d; d++) c = a[d], c.processing = !0, c.status = b.UPLOADING, this.emit("processing", c);
                    return this.options.uploadMultiple && this.emit("processingmultiple", a), this.uploadFiles(a)
                }, b.prototype._getFilesWithXhr = function(a) {
                    var b, c;
                    return c = function() {
                        var c, d, e, f;
                        for (e = this.files, f = [], c = 0, d = e.length; d > c; c++) b = e[c], b.xhr === a && f.push(b);
                        return f
                    }.call(this)
                }, b.prototype.cancelUpload = function(a) {
                    var c, d, e, f, g, h, i;
                    if (a.status === b.UPLOADING) {
                        for (d = this._getFilesWithXhr(a.xhr), e = 0, g = d.length; g > e; e++) c = d[e], c.status = b.CANCELED;
                        for (a.xhr.abort(), f = 0, h = d.length; h > f; f++) c = d[f], this.emit("canceled", c);
                        this.options.uploadMultiple && this.emit("canceledmultiple", d)
                    } else((i = a.status) === b.ADDED || i === b.QUEUED) && (a.status = b.CANCELED, this.emit("canceled", a), this.options.uploadMultiple && this.emit("canceledmultiple", [a]));
                    return this.options.autoProcessQueue ? this.processQueue() : void 0
                }, e = function() {
                    var a, b;
                    return b = arguments[0], a = 2 <= arguments.length ? j.call(arguments, 1) : [], "function" == typeof b ? b.apply(this, a) : b
                }, b.prototype.uploadFile = function(a) {
                    return this.uploadFiles([a])
                }, b.prototype.uploadFiles = function(a) {
                    var c, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J, K, L;
                    for (w = new XMLHttpRequest, x = 0, B = a.length; B > x; x++) c = a[x], c.xhr = w;
                    p = e(this.options.method, a), u = e(this.options.url, a), w.open(p, u, !0), w.withCredentials = !!this.options.withCredentials, s = null, g = function(b) {
                        return function() {
                            var d, e, f;
                            for (f = [], d = 0, e = a.length; e > d; d++) c = a[d], f.push(b._errorProcessing(a, s || b.options.dictResponseError.replace("{{statusCode}}", w.status), w));
                            return f
                        }
                    }(this), t = function(b) {
                        return function(d) {
                            var e, f, g, h, i, j, k, l, m;
                            if (null != d)
                                for (f = 100 * d.loaded / d.total, g = 0, j = a.length; j > g; g++) c = a[g], c.upload = {
                                    progress: f,
                                    total: d.total,
                                    bytesSent: d.loaded
                                };
                            else {
                                for (e = !0, f = 100, h = 0, k = a.length; k > h; h++) c = a[h], (100 !== c.upload.progress || c.upload.bytesSent !== c.upload.total) && (e = !1), c.upload.progress = f, c.upload.bytesSent = c.upload.total;
                                if (e) return
                            }
                            for (m = [], i = 0, l = a.length; l > i; i++) c = a[i], m.push(b.emit("uploadprogress", c, f, c.upload.bytesSent));
                            return m
                        }
                    }(this), w.onload = function(c) {
                        return function(d) {
                            var e;
                            if (a[0].status !== b.CANCELED && 4 === w.readyState) {
                                if (s = w.responseText, w.getResponseHeader("content-type") && ~w.getResponseHeader("content-type").indexOf("application/json")) try {
                                    s = JSON.parse(s)
                                } catch (f) {
                                    d = f, s = "Invalid JSON response from server."
                                }
                                return t(), 200 <= (e = w.status) && 300 > e ? c._finished(a, s, d) : g()
                            }
                        }
                    }(this), w.onerror = function() {
                        return function() {
                            return a[0].status !== b.CANCELED ? g() : void 0
                        }
                    }(this), r = null != (G = w.upload) ? G : w, r.onprogress = t, j = {
                        Accept: "application/json",
                        "Cache-Control": "no-cache",
                        "X-Requested-With": "XMLHttpRequest"
                    }, this.options.headers && d(j, this.options.headers);
                    for (h in j) i = j[h], w.setRequestHeader(h, i);
                    if (f = new FormData, this.options.params) {
                        H = this.options.params;
                        for (o in H) v = H[o], f.append(o, v)
                    }
                    for (y = 0, C = a.length; C > y; y++) c = a[y], this.emit("sending", c, w, f);
                    if (this.options.uploadMultiple && this.emit("sendingmultiple", a, w, f), "FORM" === this.element.tagName)
                        for (I = this.element.querySelectorAll("input, textarea, select, button"), z = 0, D = I.length; D > z; z++)
                            if (l = I[z], m = l.getAttribute("name"), n = l.getAttribute("type"), "SELECT" === l.tagName && l.hasAttribute("multiple"))
                                for (J = l.options, A = 0, E = J.length; E > A; A++) q = J[A], q.selected && f.append(m, q.value);
                            else(!n || "checkbox" !== (K = n.toLowerCase()) && "radio" !== K || l.checked) && f.append(m, l.value);
                    for (k = F = 0, L = a.length - 1; L >= 0 ? L >= F : F >= L; k = L >= 0 ? ++F : --F) f.append(this._getParamName(k), a[k], a[k].name);
                    return w.send(f)
                }, b.prototype._finished = function(a, c, d) {
                    var e, f, g;
                    for (f = 0, g = a.length; g > f; f++) e = a[f], e.status = b.SUCCESS, this.emit("success", e, c, d), this.emit("complete", e);
                    return this.options.uploadMultiple && (this.emit("successmultiple", a, c, d), this.emit("completemultiple", a)), this.options.autoProcessQueue ? this.processQueue() : void 0
                }, b.prototype._errorProcessing = function(a, c, d) {
                    var e, f, g;
                    for (f = 0, g = a.length; g > f; f++) e = a[f], e.status = b.ERROR, this.emit("error", e, c, d), this.emit("complete", e);
                    return this.options.uploadMultiple && (this.emit("errormultiple", a, c, d), this.emit("completemultiple", a)), this.options.autoProcessQueue ? this.processQueue() : void 0
                }, b
            }(c), a.version = "3.12.0", a.options = {}, a.optionsForElement = function(b) {
                return b.getAttribute("id") ? a.options[d(b.getAttribute("id"))] : void 0
            }, a.instances = [], a.forElement = function(a) {
                if ("string" == typeof a && (a = document.querySelector(a)), null == (null != a ? a.dropzone : void 0)) throw new Error("No Dropzone found for given element. This is probably because you're trying to access it before Dropzone had the time to initialize. Use the `init` option to setup any additional observers on your Dropzone.");
                return a.dropzone
            }, a.autoDiscover = !0, a.discover = function() {
                var b, c, d, e, f, g;
                for (document.querySelectorAll ? d = document.querySelectorAll(".dropzone") : (d = [], b = function(a) {
                        var b, c, e, f;
                        for (f = [], c = 0, e = a.length; e > c; c++) b = a[c], f.push(/(^| )dropzone($| )/.test(b.className) ? d.push(b) : void 0);
                        return f
                    }, b(document.getElementsByTagName("div")), b(document.getElementsByTagName("form"))), g = [], e = 0, f = d.length; f > e; e++) c = d[e], g.push(a.optionsForElement(c) !== !1 ? new a(c) : void 0);
                return g
            }, a.blacklistedBrowsers = [/opera.*Macintosh.*version\/12/i], a.isBrowserSupported = function() {
                var b, c, d, e, f;
                if (b = !0, window.File && window.FileReader && window.FileList && window.Blob && window.FormData && document.querySelector)
                    if ("classList" in document.createElement("a"))
                        for (f = a.blacklistedBrowsers, d = 0, e = f.length; e > d; d++) c = f[d], c.test(navigator.userAgent) && (b = !1);
                    else b = !1;
                else b = !1;
                return b
            }, i = function(a, b) {
                var c, d, e, f;
                for (f = [], d = 0, e = a.length; e > d; d++) c = a[d], c !== b && f.push(c);
                return f
            }, d = function(a) {
                return a.replace(/[\-_](\w)/g, function(a) {
                    return a.charAt(1).toUpperCase()
                })
            }, a.createElement = function(a) {
                var b;
                return b = document.createElement("div"), b.innerHTML = a, b.childNodes[0]
            }, a.elementInside = function(a, b) {
                if (a === b) return !0;
                for (; a = a.parentNode;)
                    if (a === b) return !0;
                return !1
            }, a.getElement = function(a, b) {
                var c;
                if ("string" == typeof a ? c = document.querySelector(a) : null != a.nodeType && (c = a), null == c) throw new Error("Invalid `" + b + "` option provided. Please provide a CSS selector or a plain HTML element.");
                return c
            }, a.getElements = function(a, b) {
                var c, d, e, f, g, h, i, j;
                if (a instanceof Array) {
                    e = [];
                    try {
                        for (f = 0, h = a.length; h > f; f++) d = a[f], e.push(this.getElement(d, b))
                    } catch (k) {
                        c = k, e = null
                    }
                } else if ("string" == typeof a)
                    for (e = [], j = document.querySelectorAll(a), g = 0, i = j.length; i > g; g++) d = j[g], e.push(d);
                else null != a.nodeType && (e = [a]);
                if (null == e || !e.length) throw new Error("Invalid `" + b + "` option provided. Please provide a CSS selector, a plain HTML element or a list of those.");
                return e
            }, a.confirm = function(a, b, c) {
                return window.confirm(a) ? b() : null != c ? c() : void 0
            }, a.isValidFile = function(a, b) {
                var c, d, e, f, g;
                if (!b) return !0;
                for (b = b.split(","), d = a.type, c = d.replace(/\/.*$/, ""), f = 0, g = b.length; g > f; f++)
                    if (e = b[f], e = e.trim(), "." === e.charAt(0)) {
                        if (-1 !== a.name.toLowerCase().indexOf(e.toLowerCase(), a.name.length - e.length)) return !0
                    } else if (/\/\*$/.test(e)) {
                    if (c === e.replace(/\/.*$/, "")) return !0
                } else if (d === e) return !0;
                return !1
            }, "undefined" != typeof jQuery && null !== jQuery && (jQuery.fn.dropzone = function(b) {
                return this.each(function() {
                    return new a(this, b)
                })
            }), "undefined" != typeof b && null !== b ? b.exports = a : window.Dropzone = a, a.ADDED = "added", a.QUEUED = "queued", a.ACCEPTED = a.QUEUED, a.UPLOADING = "uploading", a.PROCESSING = a.UPLOADING, a.CANCELED = "canceled", a.ERROR = "error", a.SUCCESS = "success", f = function(a) {
                var b, c, d, e, f, g, h, i, j, k;
                for (h = a.naturalWidth, g = a.naturalHeight, c = document.createElement("canvas"), c.width = 1, c.height = g, d = c.getContext("2d"), d.drawImage(a, 0, 0), e = d.getImageData(0, 0, 1, g).data, k = 0, f = g, i = g; i > k;) b = e[4 * (i - 1) + 3], 0 === b ? f = i : k = i, i = f + k >> 1;
                return j = i / g, 0 === j ? 1 : j
            }, g = function(a, b, c, d, e, g, h, i, j, k) {
                var l;
                return l = f(b), a.drawImage(b, c, d, e, g, h, i, j, k / l)
            }, e = function(a, b) {
                var c, d, e, f, g, h, i, j, k;
                if (e = !1, k = !0, d = a.document, j = d.documentElement, c = d.addEventListener ? "addEventListener" : "attachEvent", i = d.addEventListener ? "removeEventListener" : "detachEvent", h = d.addEventListener ? "" : "on", f = function(c) {
                        return "readystatechange" !== c.type || "complete" === d.readyState ? (("load" === c.type ? a : d)[i](h + c.type, f, !1), !e && (e = !0) ? b.call(a, c.type || c) : void 0) : void 0
                    }, g = function() {
                        var a;
                        try {
                            j.doScroll("left")
                        } catch (b) {
                            return a = b, void setTimeout(g, 50)
                        }
                        return f("poll")
                    }, "complete" !== d.readyState) {
                    if (d.createEventObject && j.doScroll) {
                        try {
                            k = !a.frameElement
                        } catch (l) {}
                        k && g()
                    }
                    return d[c](h + "DOMContentLoaded", f, !1), d[c](h + "readystatechange", f, !1), a[c](h + "load", f, !1)
                }
            }, a._autoDiscoverFunction = function() {
                return a.autoDiscover ? a.discover() : void 0
            }, e(window, a._autoDiscoverFunction)
        }).call(this)
    }), "object" == typeof exports ? module.exports = a("dropzone") : "function" == typeof define && define.amd ? define([], function() {
        return a("dropzone")
    }) : this.Dropzone = a("dropzone")
}();
