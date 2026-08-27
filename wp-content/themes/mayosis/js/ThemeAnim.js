class MayosisAnim {
    constructor() {
        this.initGSAP();
        this.initImageReveal();
        this.initTextAnimation();
        this.initCharComeAnimation();
        this.initWordAnimation();
        this.initFadeAnimation();
        this.initTextRevealAnimation();
        this.initImageGroupScale();
        this.initHeroTitlePin();
        this.initImageZoom();
    }

    initGSAP() {
        gsap.registerPlugin(ScrollTrigger);
    }

    initImageReveal() {
        const imgAnimReveal = document.querySelectorAll(".img_anim_reveal");

        imgAnimReveal.forEach((imgReveal) => {
            const image = imgReveal.querySelector("img");
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: imgReveal,
                    start: "top 50%"
                }
            });

            tl.set(imgReveal, { autoAlpha: 1 });
            tl.from(imgReveal, { duration: 1.5, xPercent: -100, ease: "power2.out" });
            tl.from(image, { duration: 1.5, xPercent: 100, scale: 1.3, delay: -1.5, ease: "power2.out" });
        });
    }

    initTextAnimation() {
        const textAnimation = gsap.utils.toArray(".has_text_move_anim");

        textAnimation.forEach((t) => {
            const delay = t.getAttribute("data-delay") || 0.5;
            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: t,
                    start: "top 85%",
                    duration: 1.5,
                    scrub: false,
                    markers: false,
                    toggleActions: "play none none none"
                }
            });

            const splitText = new SplitText(t, { type: "lines" });
            gsap.set(t, { perspective: 400 });
            splitText.split({ type: "lines" });

            tl.from(splitText.lines, {
                duration: 1,
                delay: delay,
                opacity: 0,
                rotationX: -80,
                force3D: true,
                transformOrigin: "top center -50",
                stagger: 0.1
            });
        });
    }

    initCharComeAnimation() {
        const charComeItems = document.querySelectorAll(".has_char_anim");

        charComeItems.forEach((t) => {
            const config = {
                stagger: t.getAttribute("data-stagger") || 0.05,
                translateX: t.getAttribute("data-translateX") || 20,
                translateY: t.getAttribute("data-translateY") || 0,
                onScroll: t.getAttribute("data-on-scroll") || 1,
                delay: t.getAttribute("data-delay") || 0.5,
                ease: t.getAttribute("data-ease") || "power2.out"
            };

            const splitText = new SplitText(t, { type: "chars, words" });
            const animation = { duration: 1, delay: config.delay, autoAlpha: 0, ease: config.ease, stagger: config.stagger };

            if (config.onScroll == 1) {
                animation.scrollTrigger = { trigger: t, start: "top 85%" };
            }

            if (config.translateX && !config.translateY) {
                animation.x = config.translateX;
            } else if (config.translateY && !config.translateX) {
                animation.y = config.translateY;
            } else if (config.translateX && config.translateY) {
                animation.x = config.translateX;
                animation.y = config.translateY;
                animation.duration = 3;
            } else {
                animation.x = 50;
            }

            gsap.from(splitText.chars, animation);
        });
    }

    initWordAnimation() {
        const wordAnimItems = document.querySelectorAll(".has_word_anim");

        wordAnimItems.forEach((t) => {
            const config = {
                stagger: t.getAttribute("data-stagger") || 0.05,
                translateX: t.getAttribute("data-translateX") || false,
                translateY: t.getAttribute("data-translateY") || false,
                onScroll: t.getAttribute("data-on-scroll") || 1,
                delay: t.getAttribute("data-delay") || 0.5
            };

            const splitText = new SplitText(t, { type: "chars, words" });
            const animation = { duration: 1, delay: config.delay, autoAlpha: 0, stagger: config.stagger };

            if (config.onScroll == 1) {
                animation.scrollTrigger = { trigger: t, start: "top 90%" };
            }

            if (config.translateX && !config.translateY) {
                animation.x = config.translateX;
            } else if (config.translateY && !config.translateX) {
                animation.y = config.translateY;
            } else if (config.translateX && config.translateY) {
                animation.x = config.translateX;
                animation.y = config.translateY;
            } else {
                animation.x = 20;
            }

            gsap.from(splitText.words, animation);
        });
    }

    initFadeAnimation() {
        const fadeArray = gsap.utils.toArray(".has_fade_anim");

        fadeArray.forEach((t) => {
            const config = {
                offset: t.getAttribute("data-fade-offset") || 50,
                duration: t.getAttribute("data-duration") || 1.5,
                from: t.getAttribute("data-fade-from") || "bottom",
                onScroll: t.getAttribute("data-on-scroll") || 1,
                delay: t.getAttribute("data-delay") || 0.1,
                ease: t.getAttribute("data-ease") || "power2.out"
            };

            const animation = { opacity: 0, ease: config.ease, duration: config.duration, delay: config.delay };

            if (config.from === "top") {
                animation.y = -config.offset;
            } else if (config.from === "left") {
                animation.x = -config.offset;
            } else if (config.from === "right") {
                animation.x = config.offset;
            } else if (config.from === "bottom") {
                animation.y = config.offset;
            }

            if (config.onScroll == 1) {
                animation.scrollTrigger = { trigger: t, start: "top 85%" };
            }

            gsap.from(t, animation);
        });
    }

    initTextRevealAnimation() {
        const animReveal = document.querySelectorAll(".has_text_reveal_anim");

        animReveal.forEach((t) => {
            const config = {
                duration: t.getAttribute("data-duration") || 1.5,
                onScroll: t.getAttribute("data-on-scroll") || 1,
                stagger: t.getAttribute("data-stagger") || 0.02,
                delay: t.getAttribute("data-delay") || 0.05
            };

            t.split = new SplitText(t, { type: "lines,words,chars", linesClass: "anim-reveal-line" });

            const animation = {
                duration: config.duration,
                delay: config.delay,
                ease: "circ.out",
                y: 80,
                stagger: config.stagger,
                opacity: 0
            };

            if (config.onScroll == 1) {
                animation.scrollTrigger = { trigger: t, start: "top 85%" };
            }

            gsap.from(t.split.chars, animation);
        });
    }

    initImageGroupScale() {
        const photoGallery = document.querySelectorAll(".img_anim_group_scale img");
        gsap.set(".img_anim_group_scale img", { scale: 1 });

        photoGallery.forEach((t) => {
            gsap.to(t, {
                scrollTrigger: { trigger: t, start: "top 90%", scrub: true },
                scale: 0.9
            });
        });
    }

    initHeroTitlePin() {
        gsap.to(".intDesigner-hero__titlewrap", {
            scrollTrigger: {
                trigger: ".intDesigner-hero__area",
                pin: ".intDesigner-hero__titlewrap",
                pinSpacing: false,
                start: "top top",
                end: "bottom 89",
                delay: 1,
                color: "#333"
            }
        });
    }

    initImageZoom() {
        const hasImageZoom = document.querySelectorAll(".has_image_zoom img");

        hasImageZoom.forEach((t) => {
            gsap.to(t, {
                scrollTrigger: { trigger: t, start: "top top", scrub: true },
                scale: 1.15
            });
        });
    }
}

document.addEventListener("DOMContentLoaded", () => {
    new MayosisAnim();
});
