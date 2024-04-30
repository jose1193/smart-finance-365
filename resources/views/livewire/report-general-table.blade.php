<div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">



    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- MENU SIDEBAR -->
        <x-menu-sidebar />
        <!-- END MENU SIDEBAR -->
        <div class="flex flex-col flex-1 w-full">

            <!-- HEADER -->
            <x-header-dashboard />
            <!-- END HEADER -->

            <!-- PANEL MAIN  -->


            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">

                    <!-- CTA -->
                    <div
                        class="mt-5 flex items-center justify-between p-4 mb-8 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
                        <div class="flex items-center">
                            <i class="fa-solid fa-money-bills mr-3"></i>

                            <x-slot name="title">
                                {{ __('messages.general_report_table') }}

                            </x-slot>
                            <a href="{{ route('general-report') }}">
                                <span>{{ __('messages.general_report_table') }}
                                </span></a>
                        </div>

                    </div>

                    <!-- PANEL TAB ALPINE JS -->
                    <div x-data="{ activeTab: '1' }" class="overflow-x-auto">
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
                                    {{ __('messages.categories_chart') }}
                                </button>
                            </li>

                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '3'" :class="{ 'bg-blue-700': activeTab === '3' }">
                                    {{ __('messages.months') }}
                                </button>
                            </li>

                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '4'" :class="{ 'bg-blue-700': activeTab === '4' }">
                                    {{ __('messages.budget_income') }}
                                </button>
                            </li>
                            <li class="mr-2">
                                <button
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm lg:text-base"
                                    @click="activeTab = '5'" :class="{ 'bg-blue-700': activeTab === '5' }">
                                    {{ __('messages.budget_expense') }}
                                </button>
                            </li>
                        </ul>

                        <div class="mt-7">

                            <!-- REPORT GENERAL TABLE  -->

                            <!-- REPORT GENERAL CATEGORIES TABLE  -->
                            <!-- Livewire/ReportGeneralCategoriesTable.php  -->
                            @livewire('report-general-categories-table')


                            <!-- REPORT MONTHS TABLE  -->
                            <!-- Livewire/ReportGeneralMonthTable.php  -->
                            @livewire('report-general-month-table')

                            <!-- REPORT BUDGETS TABLE  -->
                            <!-- Livewire/ReportGeneralBudgetsTable.php  -->
                            @livewire('report-general-budgets-table')

                            <!-- REPORT BUDGETS MONTH TABLE  -->
                            <!-- Livewire/ReportGeneralBudgetsIncomeMonthTable.php  -->
                            @livewire('report-general-budgets-income-month-table')
                            <!-- Livewire/ReportGeneralBudgetsMonthTable.php  -->
                            @livewire('report-general-budgets-month-table')
                            <!-- REPORT BUDGETS INCOME MONTH TABLE  -->


                        </div>
                    </div>
                    <!-- END PANEL TAB ALPINE JS -->

                </div>
            </main>


            <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>



            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>
