"use strict";

var RadialProgressBar = new Class({
    Implements: [Options],

    options: {
        backgroundColor: '#222',
        borderColor: '#ff6347',
        overlayColor: '#fff',
        fontColor: '#000',
        fontSize: '16px',
        elementSize: 100,
        borderWidth: 20,
        animate: false,
        animationSpeed: 1000,
        showText: true,
        animateText: false,
        autoStart: true,
        watch: false,
        watchInterval: 200,
        stopWatchAt100: true
    },

    initialize: function (element, options) {
        var self = this;

        this.setOptions(options);

        this.options.elementSize = parseInt(this.options.elementSize, 10);
        this.options.borderWidth = parseInt(this.options.borderWidth, 10);
        this.animateInterval = [];

        if (typeof element.length === 'number') {
            this.element = [];

            Array.each(element, function (el) {
                self.element.push(el);
                self.prepareElement(el);
            });
        } else {
            this.element = element;
            this.prepareElement(element);
        }
    },

    prepareElement: function (el) {
        var progress = this.options.initialProgress = el.get('data-progress'),
            overlay,
            elSize = this.options.elementSize - (this.options.borderWidth * 2) + 'px';

        if (el.getElement('.overlay')) {
            el.innerHTML = '';
        }

        overlay = new Element('div', {
            'class': 'overlay',
            html: this.options.showText && !this.options.animateText ? progress : '&nbsp;',
            styles: {
                'position': 'absolute',
                'width': elSize,
                'height': elSize,
                'background-color': this.options.overlayColor,
                'border-radius': '50%',
                'margin': this.options.borderWidth + 'px 0 0 ' + this.options.borderWidth + 'px',
                'text-align': 'center',
                'line-height': elSize,
                'font-size': this.options.fontSize,
                'color': this.options.fontColor
            }
        });
        overlay.inject(el);

        el.set({
            styles: {
                'float': 'left',
                'position': 'relative',
                'width': this.options.elementSize + 'px',
                'height': this.options.elementSize + 'px',
                'border-radius': '50%',
                'background-color': this.options.borderColor,
                'border': '0px solid ' + this.options.backgroundColor
            }
        });

        if (this.options.watch) {
            this.watch(el);
        }

        if (!this.options.animate) {
            this.setProgress(el);
        } else if (this.options.autoStart) {
            this.setAnimation(el);
        } else {
            el.setStyles({
                'background-image': this.getGradientLess(90)
            });
        }
    },

    setProgress: function (el) {
        var progress = parseInt(el.get('data-progress'), 10),
            deg = progress <= 50 ? parseInt(360 * (progress / 100) + 90, 10) : parseInt(360 * (progress / 100) - 270, 10);

        if (progress <= 50) {
            el.setStyles({
                'background-image': this.getGradientLess(deg)
            });
        } else {
            el.setStyles({
                'background-image': this.getGradientMore(deg)
            });
        }
    },

    setAnimation: function (el, startAt) {
    	startAt = startAt || 0;
    	var id = el.get('data-rpb-id') || btoa(JSON.encode(el.getPosition()) + +new Date),
            progress = parseInt(el.get('data-progress'), 10),
            deg = progress <= 50 ? parseInt(360 * (progress / 100) + 90, 10) : parseInt(360 * (progress / 100) - 270, 10),
            steps = 360 * (progress / 100),
            speedPerStep = this.options.animationSpeed / steps,
            animateText = this.options.animateText,
            i = parseInt(360 * (startAt / 100), 10),
            j,
            self = this;

        el.set('data-rpb-id', id);

        if (this.animateInterval[id]) window.clearInterval(this.animateInterval[id]);

        if (progress <= 50) {
        	this.animateInterval[id] = window.setInterval(function () {
                j = i + 90;

                el.setStyles({
                    'background-image': self.getGradientLess(j)
                });

                if (animateText) {
                    el.getElements('.overlay')[0].set('html', Math.round(i / 3.6) + '%');
                }

                if (i >= deg - 90) {
                	window.clearInterval(self.animateInterval[id]);
                }

                i++;
            }, speedPerStep);
        } else {
        	this.animateInterval[id] = window.setInterval(function () {
                if (i < 180) {
                    j = i + 90;

                    el.setStyles({
                        'background-image': self.getGradientLess(j)
                    });
                } else {
                    j = i - 270;

                    el.setStyles({
                        'background-image': self.getGradientMore(j)
                    });
                }

                if (animateText) {
                    el.getElements('.overlay')[0].set('html', Math.round(i / 3.6) + '%');
                }

                if (i >= deg + 270) {
                	window.clearInterval(self.animateInterval[id]);
                }

                i++;
            }, speedPerStep);
        }
    },

    getGradientLess: function (deg) {
        var bg = this.options.backgroundColor,
            bc = this.options.borderColor;

        return 'linear-gradient(90deg, ' + bg + ' 50%, transparent 50%, transparent), linear-gradient(' + deg + 'deg, ' + bc + ' 50%, ' + bg + ' 50%, ' + bg + ')';
    },

    getGradientMore: function (deg) {
        var bg = this.options.backgroundColor,
            bc = this.options.borderColor;

        return 'linear-gradient(' + deg + 'deg, ' + bc + ' 50%, transparent 50%, transparent), linear-gradient(270deg, ' + bc + ' 50%, ' + bg + ' 50%, ' + bg + ')';
    },

    start: function () {
        var self = this;

        if (typeof this.element.length === 'number') {
            Array.each(this.element, function (el) {
                self.setAnimation(el);
            });
        } else {
            this.setAnimation(this.element);
        }
    },

    watch: function (el) {
        var self = this;

        this.startPos = parseInt(el.get('data-progress'), 10);

        this.watchInterval = window.setInterval(function () {
            var curPos = parseInt(el.get('data-progress'), 10);

            if (curPos !== self.startPos) {
                self.setAnimation(el, self.startPos);
                self.startPos = curPos;
            }

            if (self.options.showText && !self.options.animateText) {
                self.element.getElements('.overlay')[0].set('html', curPos + '%');
            }

            if (self.options.stopWatchAt100 && curPos >= 100) {
                window.clearInterval(self.watchInterval);
            }
        }, this.options.watchInterval);
    },

    reset: function () {
        this.element.set('data-progress', this.options.initialProgress);
        this.prepareElement(this.element);
    }
});
