<div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">
    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- MENU SIDEBAR -->
        <x-menu-sidebar />
        <!-- END MENU SIDEBAR -->
        <div class="flex flex-col flex-1 w-full">

            <!-- HEADER -->
            <x-header-dashboard />
            <!-- END HEADER -->

            <!-- PANEL MAIN CATEGORIES -->
            <!--INCLUDE ALERTS MESSAGES-->

            <x-message-success />


            <!-- END INCLUDE ALERTS MESSAGES-->

            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">

                    <!-- CTA -->
                    <div
                        class="mt-5 flex items-center justify-between p-4 mb-8 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
                        <div class="flex items-center">
                            <i class="fa-solid fa-money-bills mr-3"></i>

                            <x-slot name="title">
                                {{ __('General Chart') }}
                            </x-slot>
                            <a href="{{ route('general-charts') }}">
                                <span>General Chart</span></a>
                        </div>

                    </div>



                    <div x-data="{ activeTab: '1' }">
                        <ul class="flex border-b">
                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '1'" :class="{ 'bg-blue-700': activeTab === '1' }">
                                    General
                                </button>
                            </li>
                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '2'" :class="{ 'bg-blue-700': activeTab === '2' }">
                                    Categories
                                </button>
                            </li>

                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '3'" :class="{ 'bg-blue-700': activeTab === '3' }">
                                    Months
                                </button>
                            </li>


                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '4'" :class="{ 'bg-blue-700': activeTab === '4' }">
                                    Budget Expense
                                </button>
                            </li>
                        </ul>

                        <div class="mt-4">

                            <!-- REPORT GENERAL CHARTS  -->


                            <!-- Livewire/ReportGeneralCategoriesCharts.php  -->
                            @livewire('report-general-categories-charts')

                            <!-- Livewire/ReportGeneralMonthCharts.php  -->
                            @livewire('report-general-month-charts')

                            <!-- Livewire/ReportGeneralBudgetCharts.php  -->
                            @livewire('report-general-budgets-chart')

                            <!-- Livewire/ReportGeneralBudgetExpenseCharts.php  -->
                            @livewire('report-general-budgets-expense-charts')
                        </div>
                    </div>







                </div>
            </main>


            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>
