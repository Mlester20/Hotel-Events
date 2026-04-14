document.addEventListener('DOMContentLoaded', function() {
    // Array of hero content
    const heroContentArray = [
        {
            title: "Explore! Discover! Live!",
            description: "The best hotel for your family!",
        },
        {
            title: "Relax and Unwind",
            description: "Experience luxury like never before.",
        },
        {
            title: "Your Perfect Getaway",
            description: "Create memories that last a lifetime.",
        }
    ];
    
    const heroContent = document.getElementById('heroContent');
    let currentSlideIndex = 0;
    let slideInterval;
    const slideDelay = 5000; // 5 seconds per slide
    
    // Function to update slide content
    function updateSlideContent(index) {
        const content = heroContentArray[index];
        
        // Fade out current content
        heroContent.style.opacity = 0;
        
        // Update content after a short delay
        setTimeout(() => {
            heroContent.innerHTML = `
                <h1>${content.title}</h1>
                <p>${content.description}</p>
            `;
            
            // Fade back in
            heroContent.style.opacity = 1;
        }, 500);
    }
    
    // Function to go to the next slide
    function nextSlide() {
        currentSlideIndex = (currentSlideIndex + 1) % heroContentArray.length;
        updateSlideContent(currentSlideIndex);
    }
    
    // Initialize first slide
    updateSlideContent(currentSlideIndex);
    
    // Start auto-sliding
    slideInterval = setInterval(nextSlide, slideDelay);
});