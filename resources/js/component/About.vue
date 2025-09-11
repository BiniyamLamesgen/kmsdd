<script setup lang="ts"></script>
<template>
    <section
        id="about"
        class="relative py-12 sm:py-16 lg:py-20 bg-gradient-to-br from-slate-50 via-white to-blue-50 overflow-hidden"
    >
        <!-- Background SVG Pattern -->
        <div class="absolute inset-0 opacity-[0.03]">
            <svg
                class="w-full h-full"
                viewBox="0 0 1000 1000"
                xmlns="http://www.w3.org/2000/svg"
            >
                <defs>
                    <pattern
                        id="about-grid"
                        x="0"
                        y="0"
                        width="50"
                        height="50"
                        patternUnits="userSpaceOnUse"
                    >
                        <circle cx="25" cy="25" r="2" fill="#3b82f6" />
                        <path
                            d="M0 25h50M25 0v50"
                            stroke="#3b82f6"
                            stroke-width="0.5"
                            opacity="0.3"
                        />
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#about-grid)" />
            </svg>
        </div>

        <!-- Floating Elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute top-16 sm:top-20 left-4 sm:left-10 w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-r from-blue-400 to-cyan-400 rounded-full opacity-10 animate-pulse"
            ></div>
            <div
                class="absolute top-32 sm:top-40 right-8 sm:right-20 w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-r from-indigo-400 to-purple-400 rounded-full opacity-5 animate-bounce"
                style="animation-duration: 3s"
            ></div>
            <div
                class="absolute bottom-24 sm:bottom-32 left-1/4 w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full opacity-8 animate-ping"
                style="animation-duration: 4s"
            ></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="text-center mb-12 sm:mb-16 lg:mb-20">
                <div
                    class="inline-flex items-center gap-2 sm:gap-3 bg-white rounded-full px-4 sm:px-6 lg:px-8 py-2 sm:py-3 lg:py-4 shadow-xl border border-blue-100 mb-6 sm:mb-8"
                >
                    <div
                        class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full p-2 sm:p-3"
                    >
                        <svg
                            class="w-5 h-5 sm:w-6 sm:h-6 lg:w-8 lg:h-8 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            ></path>
                        </svg>
                    </div>
                    <span
                        class="text-blue-600 font-bold text-sm sm:text-base lg:text-lg"
                        >About Lemi Kura KMS</span
                    >
                </div>

                <h2
                    class="text-3xl sm:text-4xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 sm:mb-8 leading-tight px-4"
                >
                    Transforming Organizations Through
                    <span
                        class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent block"
                    >
                        Knowledge Excellence
                    </span>
                </h2>

                <p
                    class="text-base sm:text-lg lg:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed px-4"
                >
                    Lemi Kura Knowledge Management System empowers organizations
                    to centralize, share, and grow their collective expertise,
                    fostering a culture of collaboration and continuous
                    learning.
                </p>
            </div>

            <!-- Vision, Mission, Objectives Grid -->
            <div
                class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8 mb-16 sm:mb-20"
            >
                <!-- Vision -->
                <div
                    class="group relative bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                    <div class="absolute -top-4 sm:-top-6 left-6 sm:left-8">
                        <div
                            class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-lg"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <div class="pt-6 sm:pt-8">
                        <h3
                            class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-purple-600 transition-colors"
                        >
                            Our Vision
                        </h3>
                        <p
                            class="text-gray-600 leading-relaxed text-sm sm:text-base"
                        >
                            To become the global leader in knowledge management
                            solutions, enabling organizations worldwide to
                            unlock their collective intelligence and drive
                            sustainable innovation through seamless knowledge
                            sharing and collaboration.
                        </p>
                    </div>

                    <!-- Decorative Element -->
                    <div
                        class="absolute bottom-3 sm:bottom-4 right-3 sm:right-4 opacity-10 group-hover:opacity-20 transition-opacity"
                    >
                        <svg
                            class="w-12 h-12 sm:w-16 sm:h-16 text-purple-500"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Mission -->
                <div
                    class="group relative bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:scale-105"
                >
                    <div class="absolute -top-4 sm:-top-6 left-6 sm:left-8">
                        <div
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-lg"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <div class="pt-6 sm:pt-8">
                        <h3
                            class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-blue-600 transition-colors"
                        >
                            Our Mission
                        </h3>
                        <p
                            class="text-gray-600 leading-relaxed text-sm sm:text-base"
                        >
                            To provide innovative, user-centric knowledge
                            management solutions that empower organizations to
                            capture, organize, and leverage their intellectual
                            assets effectively, creating sustainable competitive
                            advantages and fostering organizational excellence.
                        </p>
                    </div>

                    <!-- Decorative Element -->
                    <div
                        class="absolute bottom-3 sm:bottom-4 right-3 sm:right-4 opacity-10 group-hover:opacity-20 transition-opacity"
                    >
                        <svg
                            class="w-12 h-12 sm:w-16 sm:h-16 text-blue-500"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Objectives -->
                <div
                    class="group relative bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 hover:scale-105 lg:col-span-1"
                >
                    <div class="absolute -top-4 sm:-top-6 left-6 sm:left-8">
                        <div
                            class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-xl sm:rounded-2xl p-3 sm:p-4 shadow-lg"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                ></path>
                            </svg>
                        </div>
                    </div>

                    <div class="pt-6 sm:pt-8">
                        <h3
                            class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4 group-hover:text-emerald-600 transition-colors"
                        >
                            Our Objectives
                        </h3>
                        <p
                            class="text-gray-600 leading-relaxed text-sm sm:text-base"
                        >
                            To streamline knowledge workflows, enhance
                            decision-making capabilities, reduce knowledge
                            silos, and create a culture of continuous learning
                            that drives innovation and operational excellence
                            across all organizational levels.
                        </p>
                    </div>

                    <!-- Decorative Element -->
                    <div
                        class="absolute bottom-3 sm:bottom-4 right-3 sm:right-4 opacity-10 group-hover:opacity-20 transition-opacity"
                    >
                        <svg
                            class="w-12 h-12 sm:w-16 sm:h-16 text-emerald-500"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"
                            />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Knowledge Management Features -->
            <div class="mb-16 sm:mb-20">
                <div class="text-center mb-12 sm:mb-16">
                    <h3
                        class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4 sm:mb-6 px-4"
                    >
                        Comprehensive Knowledge Management
                        <span
                            class="bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent block"
                        >
                            Solution Features
                        </span>
                    </h3>
                    <p
                        class="text-lg sm:text-xl text-gray-600 max-w-3xl mx-auto px-4"
                    >
                        Our platform provides a complete ecosystem for managing
                        organizational knowledge and expertise
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8"
                >
                    <!-- Employee Profiles -->
                    <div
                        class="group relative bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 border border-blue-200 hover:border-blue-300"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl p-3 w-fit mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                ></path>
                            </svg>
                        </div>
                        <h4
                            class="text-lg sm:text-xl font-bold text-gray-900 mb-3"
                        >
                            Employee Profiles
                        </h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Comprehensive employee profiles showcasing skills,
                            experience, certifications, and achievements in one
                            centralized location.
                        </p>
                    </div>

                    <!-- Knowledge Sharing -->
                    <div
                        class="group relative bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 border border-purple-200 hover:border-purple-300"
                    >
                        <div
                            class="bg-gradient-to-r from-purple-500 to-pink-600 rounded-xl p-3 w-fit mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                                ></path>
                            </svg>
                        </div>
                        <h4
                            class="text-lg sm:text-xl font-bold text-gray-900 mb-3"
                        >
                            Knowledge Sharing
                        </h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Seamless document sharing, best practices
                            distribution, and collaborative knowledge creation
                            across teams and departments.
                        </p>
                    </div>

                    <!-- Skills & Growth Tracking -->
                    <div
                        class="group relative bg-gradient-to-br from-emerald-50 to-teal-100 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 border border-emerald-200 hover:border-emerald-300"
                    >
                        <div
                            class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-3 w-fit mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                        <h4
                            class="text-lg sm:text-xl font-bold text-gray-900 mb-3"
                        >
                            Skills & Growth
                        </h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Advanced skill tracking, career progression
                            monitoring, and personalized learning path
                            recommendations for continuous development.
                        </p>
                    </div>

                    <!-- Smart Search -->
                    <div
                        class="group relative bg-gradient-to-br from-orange-50 to-red-100 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 border border-orange-200 hover:border-orange-300"
                    >
                        <div
                            class="bg-gradient-to-r from-orange-500 to-red-600 rounded-xl p-3 w-fit mb-4 group-hover:scale-110 transition-transform"
                        >
                            <svg
                                class="w-6 h-6 sm:w-8 sm:h-8 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                ></path>
                            </svg>
                        </div>
                        <h4
                            class="text-lg sm:text-xl font-bold text-gray-900 mb-3"
                        >
                            Smart Search
                        </h4>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            AI-powered search capabilities with intelligent
                            filtering, expert discovery, and contextual content
                            recommendations.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Core Benefits -->
            <div
                class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl sm:rounded-3xl p-8 sm:p-12 text-white"
            >
                <div class="text-center mb-8 sm:mb-12">
                    <h3
                        class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6"
                    >
                        Why Choose Lemi Kura KMS?
                    </h3>
                    <p
                        class="text-lg sm:text-xl text-blue-100 max-w-3xl mx-auto px-4"
                    >
                        Experience the transformative power of organized
                        knowledge and accelerated collaboration
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                    <div class="text-center group">
                        <div
                            class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 mb-3 sm:mb-4 group-hover:bg-white/30 transition-all duration-300"
                        >
                            <svg
                                class="w-10 h-10 sm:w-12 sm:h-12 text-white mx-auto"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                ></path>
                            </svg>
                        </div>
                        <h4 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-3">
                            Increased Productivity
                        </h4>
                        <p class="text-blue-100 text-sm sm:text-base">
                            Reduce time spent searching for information and
                            expertise by 70% with our intelligent system
                        </p>
                    </div>

                    <div class="text-center group">
                        <div
                            class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 mb-3 sm:mb-4 group-hover:bg-white/30 transition-all duration-300"
                        >
                            <svg
                                class="w-10 h-10 sm:w-12 sm:h-12 text-white mx-auto"
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
                        </div>
                        <h4 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-3">
                            Enhanced Collaboration
                        </h4>
                        <p class="text-blue-100 text-sm sm:text-base">
                            Break down silos and foster cross-team collaboration
                            with unified knowledge access
                        </p>
                    </div>

                    <div class="text-center group">
                        <div
                            class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 mb-3 sm:mb-4 group-hover:bg-white/30 transition-all duration-300"
                        >
                            <svg
                                class="w-10 h-10 sm:w-12 sm:h-12 text-white mx-auto"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                ></path>
                            </svg>
                        </div>
                        <h4 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-3">
                            Innovation Catalyst
                        </h4>
                        <p class="text-blue-100 text-sm sm:text-base">
                            Accelerate innovation by connecting ideas,
                            expertise, and resources across your organization
                        </p>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="text-center mt-16 sm:mt-20">
                <h3
                    class="text-2xl sm:text-3xl font-bold text-gray-900 mb-4 sm:mb-6 px-4"
                >
                    Ready to Transform Your Organization?
                </h3>
                <p
                    class="text-lg sm:text-xl text-gray-600 mb-6 sm:mb-8 max-w-2xl mx-auto px-4"
                >
                    Join thousands of organizations already benefiting from our
                    knowledge management solutions
                </p>
                <div
                    class="flex flex-col sm:flex-row gap-4 justify-center px-4"
                >
                    <a
                        href="/frontpage"
                        class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-semibold text-base sm:text-lg shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5 mr-2"
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
                        Explore Our Platform
                    </a>
                    <a
                        href="#gallery"
                        class="inline-flex items-center justify-center px-6 sm:px-8 py-3 sm:py-4 border-2 border-blue-600 text-blue-600 rounded-xl font-semibold text-base sm:text-lg hover:bg-blue-600 hover:text-white transition-all duration-300 transform hover:scale-105"
                    >
                        <svg
                            class="w-4 h-4 sm:w-5 sm:h-5 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                            ></path>
                        </svg>
                        View Gallery
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
