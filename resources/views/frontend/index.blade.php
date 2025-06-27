<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Multi-Level Responsive Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        .submenu,
        .submenu-right {
            display: none;
            position: absolute;
            min-width: 220px;
            z-index: 50;
            border-radius: 0.25rem;
            background-color: white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .submenu {
            top: 100%;
            left: 0;
            margin-top: 0.5rem;
        }

        .submenu-right {
            top: 0;
            left: 100%;
            margin-left: 0.25rem;
        }

        .caret {
            margin-left: 0.4rem;
            transition: transform 0.3s ease;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .caret-down {
            transform: rotate(0deg);
        }

        .caret-up {
            transform: rotate(180deg);
        }

        .caret-right {
            transform: rotate(90deg);
        }

        .caret-left {
            transform: rotate(-90deg);
        }

        @media (max-width: 768px) {
            ul.main-menu {
                display: none !important;
            }

            #mobile-menu {
                display: block;
                background-color: white;
            }

            .mobile-submenu {
                background-color: white;
                border-left: 2px solid #e5e7eb;
                margin-left: 0.5rem;
                padding-left: 0.5rem;
            }

            .mobile-toggle {
                background-color: #f1f5f9;
                color: #1f2937;
                border-radius: 0.25rem;
            }

            .mobile-toggle:hover {
                background-color: #e2e8f0;
            }
        }
    </style>
</head>

<body class="bg-white">
    <nav class="bg-blue-600 shadow sticky top-0 z-50 w-full">
        <div class="max-w-7xl mx-auto px-4 flex items-center h-16 justify-between w-full">
            <!-- Logo -->
            <div>
                <a href="#" class="text-xl font-bold text-white">MyLogo</a>
            </div>

            <!-- Desktop Menu -->
            <div class="flex-1 flex justify-end">
                <ul class="main-menu flex space-x-6 font-medium text-white">
                    @php
                        function renderMenu($items, $groupedChildren, $level = 0)
                        {
                            foreach ($items as $item) {
                                $hasChildren = isset($groupedChildren[$item->id]);
                                echo '<li class="menu-item relative group">';
                                $textColor =
                                    $level === 0 ? 'text-white hover:text-gray-200' : 'text-black hover:text-blue-600';
                                if ($hasChildren) {
                                    $caretClass = $level === 0 ? 'caret caret-down' : 'caret caret-right';
                                    echo '<a href="#" class="flex items-center w-full px-4 py-2 ' .
                                        $textColor .
                                        '">' .
                                        $item->name .
                                        '<svg class="' .
                                        $caretClass .
                                        '" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                          <path d="M6 9l6 6 6-6" />
                        </svg>
                      </a>';
                                    $submenuClass = $level === 0 ? 'submenu' : 'submenu-right';
                                    echo '<ul class="' . $submenuClass . '">';
                                    renderMenu($groupedChildren[$item->id], $groupedChildren, $level + 1);
                                    echo '</ul>';
                                } else {
                                    echo '<a href="' .
                                        $item->url .
                                        '" class="block px-4 py-2 ' .
                                        $textColor .
                                        '">' .
                                        $item->name .
                                        '</a>';
                                }
                                echo '</li>';
                            }
                        }
                        renderMenu($parents, $groupedChildren);
                    @endphp
                </ul>
            </div>

            <!-- Mobile Toggle -->
            <button id="menu-btn" class="md:hidden focus:outline-none text-white ml-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="shadow-md px-4 pt-4 pb-6 md:hidden hidden">
            @php
                function renderMobileMenu($items, $groupedChildren, $level = 0)
                {
                    foreach ($items as $item) {
                        $hasChildren = isset($groupedChildren[$item->id]);
                        echo '<div class="mb-1">';
                        if ($hasChildren) {
                            echo '<a href="#" class="mobile-toggle flex justify-between items-center w-full px-4 py-2">' .
                                $item->name .
                                '<svg class="caret caret-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="16" height="16">
                        <path d="M6 9l6 6 6-6" />
                    </svg>
                </a>';
                            echo '<div class="mobile-submenu hidden pl-4">';
                            renderMobileMenu($groupedChildren[$item->id], $groupedChildren, $level + 1);
                            echo '</div>';
                        } else {
                            echo '<a href="' .
                                $item->url .
                                '" class="block px-4 py-2 hover:text-blue-600">' .
                                $item->name .
                                '</a>';
                        }
                        echo '</div>';
                    }
                }
                renderMobileMenu($parents, $groupedChildren);
            @endphp
        </div>
    </nav>

    <script>
        $(document).ready(function() {
            $('#menu-btn').click(function() {
                $('#mobile-menu').slideToggle();
            });

            $('.menu-item').hover(
                function() {
                    $(this).children('.submenu').stop(true, true).fadeIn(150);
                    $(this).children('a').find('.caret-down').addClass('caret-up');
                },
                function() {
                    $(this).children('.submenu').stop(true, true).fadeOut(150);
                    $(this).children('a').find('.caret-down').removeClass('caret-up');
                }
            );

            $('.submenu li.menu-item').hover(
                function() {
                    $(this).children('.submenu-right').stop(true, true).fadeIn(150);
                    $(this).children('a').find('.caret-right').addClass('caret-left');
                },
                function() {
                    $(this).children('.submenu-right').stop(true, true).fadeOut(150);
                    $(this).children('a').find('.caret-right').removeClass('caret-left');
                }
            );

            $('.mobile-toggle').click(function(e) {
                e.preventDefault();
                $(this).next('.mobile-submenu').slideToggle();
                $(this).find('.caret').toggleClass('caret-up');
            });
        });
    </script>
</body>

</html>
