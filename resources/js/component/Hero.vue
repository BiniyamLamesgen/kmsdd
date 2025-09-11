<script setup lang="ts">
const props = defineProps<{
    employees: number;
    skills: number;
}>();
</script>

<style scoped>
/* Add any component-specific styles here */
</style>
<template>
    <section
        class="relative min-h-screen flex items-center justify-center overflow-hidden bg-white"
    >
        <!-- Animated Background SVG -->
        <div class="absolute inset-0 w-full h-full">
            <!-- Background Pattern -->
            <svg
                class="absolute inset-0 w-full h-full"
                viewBox="0 0 1000 1000"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <linearGradient
                        id="grad1"
                        x1="0%"
                        y1="0%"
                        x2="100%"
                        y2="100%"
                    >
                        <stop
                            offset="0%"
                            style="stop-color: #3b82f6; stop-opacity: 0.1"
                        />
                        <stop
                            offset="50%"
                            style="stop-color: #60a5fa; stop-opacity: 0.05"
                        />
                        <stop
                            offset="100%"
                            style="stop-color: #93c5fd; stop-opacity: 0.03"
                        />
                    </linearGradient>
                    <linearGradient
                        id="grad2"
                        x1="100%"
                        y1="0%"
                        x2="0%"
                        y2="100%"
                    >
                        <stop
                            offset="0%"
                            style="stop-color: #06b6d4; stop-opacity: 0.08"
                        />
                        <stop
                            offset="50%"
                            style="stop-color: #22d3ee; stop-opacity: 0.04"
                        />
                        <stop
                            offset="100%"
                            style="stop-color: #67e8f9; stop-opacity: 0.02"
                        />
                    </linearGradient>
                </defs>

                <!-- Floating Geometric Shapes -->
                <circle
                    cx="100"
                    cy="100"
                    r="50"
                    fill="url(#grad1)"
                    class="animate-float-slow"
                >
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        values="0,0; 20,10; 0,0"
                        dur="6s"
                        repeatCount="indefinite"
                    />
                </circle>
                <circle
                    cx="900"
                    cy="200"
                    r="80"
                    fill="url(#grad2)"
                    class="animate-float-medium"
                >
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        values="0,0; -15,25; 0,0"
                        dur="8s"
                        repeatCount="indefinite"
                    />
                </circle>
                <circle
                    cx="200"
                    cy="800"
                    r="60"
                    fill="url(#grad1)"
                    class="animate-float-fast"
                >
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        values="0,0; 30,-20; 0,0"
                        dur="5s"
                        repeatCount="indefinite"
                    />
                </circle>
                <circle
                    cx="800"
                    cy="700"
                    r="70"
                    fill="url(#grad2)"
                    class="animate-float-slow"
                >
                    <animateTransform
                        attributeName="transform"
                        type="translate"
                        values="0,0; -25,15; 0,0"
                        dur="7s"
                        repeatCount="indefinite"
                    />
                </circle>

                <!-- Wavy Lines -->
                <path
                    d="M0,500 Q250,400 500,500 T1000,500"
                    stroke="rgba(59, 130, 246, 0.15)"
                    stroke-width="2"
                    fill="none"
                    class="animate-pulse"
                >
                    <animate
                        attributeName="d"
                        values="M0,500 Q250,400 500,500 T1000,500; M0,500 Q250,600 500,500 T1000,500; M0,500 Q250,400 500,500 T1000,500"
                        dur="10s"
                        repeatCount="indefinite"
                    />
                </path>
                <path
                    d="M0,300 Q250,200 500,300 T1000,300"
                    stroke="rgba(6, 182, 212, 0.1)"
                    stroke-width="3"
                    fill="none"
                >
                    <animate
                        attributeName="d"
                        values="M0,300 Q250,200 500,300 T1000,300; M0,300 Q250,400 500,300 T1000,300; M0,300 Q250,200 500,300 T1000,300"
                        dur="12s"
                        repeatCount="indefinite"
                    />
                </path>

                <!-- Abstract Network Nodes -->
                <g opacity="0.2">
                    <line
                        x1="150"
                        y1="150"
                        x2="300"
                        y2="250"
                        stroke="#3b82f6"
                        stroke-width="1"
                    >
                        <animate
                            attributeName="opacity"
                            values="0.1;0.4;0.1"
                            dur="4s"
                            repeatCount="indefinite"
                        />
                    </line>
                    <line
                        x1="700"
                        y1="150"
                        x2="850"
                        y2="300"
                        stroke="#06b6d4"
                        stroke-width="1"
                    >
                        <animate
                            attributeName="opacity"
                            values="0.4;0.1;0.4"
                            dur="5s"
                            repeatCount="indefinite"
                        />
                    </line>
                    <line
                        x1="200"
                        y1="600"
                        x2="400"
                        y2="750"
                        stroke="#3b82f6"
                        stroke-width="1"
                    >
                        <animate
                            attributeName="opacity"
                            values="0.15;0.45;0.15"
                            dur="6s"
                            repeatCount="indefinite"
                        />
                    </line>
                </g>
            </svg>
        </div>

        <!-- Content Container -->
        <div
            class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 items-center gap-8 sm:gap-12 lg:gap-16 py-12 sm:py-16 lg:py-20"
        >
            <!-- Text Content -->
            <div
                class="text-gray-900 space-y-6 sm:space-y-8 text-center lg:text-left"
            >
                <div class="space-y-4 sm:space-y-6">
                    <h1
                        class="text-4xl sm:text-5xl md:text-5xl lg:text-5xl font-bold leading-tight"
                    >
                        <span
                            class="block bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent"
                        >
                            Empower Your
                        </span>
                        <span class="block text-slate-900">
                            Organization's
                        </span>
                        <span
                            class="block bg-gradient-to-r from-cyan-500 to-blue-500 bg-clip-text text-transparent"
                        >
                            Knowledge
                        </span>
                    </h1>

                    <p
                        class="text-lg sm:text-xl lg:text-xl text-gray-700 leading-relaxed max-w-2xl mx-auto lg:mx-0"
                    >
                        Discover, share, and grow your team's expertise with the
                        <span class="font-semibold text-blue-600"
                            >Lemi Kura Knowledge Management System</span
                        >. Centralize employee skills, achievements, and
                        resources to foster collaboration and innovation.
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div
                    class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center lg:justify-start"
                >
                    <a
                        href="/frontpage"
                        class="group relative px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 font-semibold text-base sm:text-lg transform hover:scale-105 overflow-hidden"
                    >
                        <span
                            class="relative z-10 flex items-center justify-center gap-2"
                        >
                            <svg
                                class="w-4 h-4 sm:w-5 sm:h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                            Explore Talent
                        </span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                        ></div>
                    </a>

                    <a
                        href="#about"
                        class="group px-6 sm:px-8 py-3 sm:py-4 border-2 border-blue-500 text-blue-600 rounded-xl hover:bg-blue-500 hover:text-white transition-all duration-300 font-semibold text-base sm:text-lg transform hover:scale-105 flex items-center justify-center gap-2"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        Learn More
                    </a>
                </div>

                <!-- Stats or Features -->
                <div
                    class="grid grid-cols-3 gap-4 sm:gap-6 pt-6 sm:pt-8 border-t border-gray-200 max-w-md mx-auto lg:mx-0"
                >
                    <div class="text-center">
                        <div
                            class="text-2xl sm:text-3xl font-bold text-blue-600"
                        >
                            {{ props.employees }}+
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500">
                            Employees
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="text-2xl sm:text-3xl font-bold text-blue-600"
                        >
                            {{ props.skills }}+
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500">
                            Skills Tracked
                        </div>
                    </div>
                    <div class="text-center">
                        <div
                            class="text-2xl sm:text-3xl font-bold text-blue-600"
                        >
                            24 / 7
                        </div>
                        <div class="text-xs sm:text-sm text-gray-500">
                            Access
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image/Illustration -->
            <div
                class="relative flex justify-center items-center order-first lg:order-last"
            >
                <div class="relative">
                    <!-- Glowing background effect -->
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full opacity-20 blur-3xl scale-110 animate-pulse"
                    ></div>

                    <!-- Main image container -->
                    <div
                        class="relative bg-white/10 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 border border-white/20 shadow-2xl"
                    >
                        <img
                            src="/images/landingimage.png"
                            alt="Knowledge management system dashboard illustration"
                            class="rounded-xl w-full max-w-sm sm:max-w-md lg:max-w-xl h-auto transform hover:scale-105 transition-transform duration-500"
                        />
                    </div>

                    <!-- Floating elements around the image -->
                    <div
                        class="absolute -top-4 sm:-top-6 -right-4 sm:-right-6 w-8 h-8 sm:w-12 sm:h-12 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full opacity-80 animate-bounce"
                    ></div>
                    <div
                        class="absolute -bottom-3 sm:-bottom-4 -left-3 sm:-left-4 w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full opacity-60 animate-pulse"
                    ></div>
                    <div
                        class="absolute top-1/2 -left-6 sm:-left-8 w-4 h-4 sm:w-6 sm:h-6 bg-gradient-to-r from-cyan-300 to-blue-300 rounded-full opacity-70 animate-ping"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div
            class="absolute bottom-6 sm:bottom-8 left-1/2 transform -translate-x-1/2 text-blue-500 animate-bounce"
        >
            <svg
                class="w-5 h-5 sm:w-6 sm:h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 14l-7 7m0 0l-7-7m7 7V3"
                ></path>
            </svg>
        </div>
    </section>
</template>
