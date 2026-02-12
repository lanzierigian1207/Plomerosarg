(function($){
gsap.registerPlugin(ScrollTrigger );
	gsap.config({
    nullTargetWarn: false,
    trialWarn: false
});

// Split Text Spans
function splitTextToSpans(element) {
    function wrapTextNodes(node) {
        if (node.nodeType === Node.TEXT_NODE) {
            const words = node.textContent.trim().split(/\s+/);
            return words.map(word => {
                const chars = word.split("").map(char => `<span class="split-line">${char}</span>`).join("");
                return `<span class="word">${chars}&nbsp;</span>`;
            }).join("");
        } else if (node.nodeType === Node.ELEMENT_NODE) {
            return `<${node.tagName.toLowerCase()} class="${node.className}">${Array.from(node.childNodes).map(wrapTextNodes).join("")}</${node.tagName.toLowerCase()}>`;
        }
        return "";
    }
    
    element.innerHTML = Array.from(element.childNodes).map(wrapTextNodes).join("");
}

function pbmit_title_animation() {
    ScrollTrigger.matchMedia({
        "(min-width: 991px)": function() {
            const quotes = document.querySelectorAll(".title-container .title");
            quotes.forEach(quote => {
                // Reset if needed
                if (quote.animation) {
                    quote.animation.progress(1).kill();
                    quote.innerHTML = quote.dataset.originalText;
                } else {
                    quote.dataset.originalText = quote.innerHTML;
                }

                var getclass = quote.closest('.title-container').className;
                var animation = getclass.split('animation-');
                if (animation[1] == "style4") return;

                splitTextToSpans(quote);
                let charElements = quote.querySelectorAll(".split-line");
                
                gsap.set(charElements, { opacity: 0 });
            
                if (animation[1] == "style1") {
                    gsap.set(charElements, {
                        opacity: 0,
                        y: "90%",
                        rotateX: "-40deg"
                    });
                }
                if (animation[1] == "style2") {
                    gsap.set(charElements, {
                        opacity: 0,
                        x: "50"
                    });
                }
                if (animation[1] == "style3") {
                    gsap.set(charElements, {
                        opacity: 0
                    });
                }
                // Apply GSAP animation
                quote.animation = gsap.to(charElements, {
                    scrollTrigger: {
                        trigger: quote,
                        start: "top 90%",
                    },
                    x: "0",
                    y: "0",
                    rotateX: "0",
                    opacity: 1,
                    duration: 1,
                    ease: "back.out(1.7)",
                    stagger: 0.02
                });
            });
        }
    });
}

ScrollTrigger.matchMedia({
    "(max-width: 100px)": function() {
        ScrollTrigger.getAll().forEach(t => t.kill());
    }
});

$(document).ready(function() {
    pbmit_title_animation();
});

$(window).resize(function() {
    pbmit_title_animation();
});


$(window).on('load', function(){
    setTimeout(() => ScrollTrigger.refresh(), 500);
});
})(jQuery);