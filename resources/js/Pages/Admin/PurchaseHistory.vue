<script setup>
import { ref, defineComponent } from "vue";
import Layout from "@layouts/AdminLayout.vue";
import { Head, router, Link } from "@inertiajs/vue3";

const props = defineProps(["donates", "currentPage", "paypalDonates"]);

defineComponent({
  Head,
});

const renderPage = (page) => {
  page.url = page.url.replace("http", "https");
  router.visit(page.url, {
    preserveScroll: true,
  });
};
</script>
<template>
  <layout>
    <template v-slot:title> DASHBOARD </template>
    <template v-slot:content>
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header bg-dark">
              <h5 class="text-white">Paypal Donates</h5>
            </div>
            <div class="card-body">
              <table class="table table-stirpe table-hover border">
                <thead>
                  <tr>
                    <th class="text-uppercase fw-bold bg-dark text-white">Token</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">
                      Transaction Status
                    </th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Account</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Date</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Amount</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">
                      Cash In Status
                    </th>
                    <th class="text-uppercase fw-bold bg-dark text-white">
                      Current Cash
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="paypalDonate in paypalDonates" :key="paypalDonate">
                    <td>{{ paypalDonate.tx }}</td>
                    <td>{{ paypalDonate.payment_status }}</td>
                    <td>
                      {{ paypalDonate.memebr_id }}
                    </td>
                    <td>{{ paypalDonate.created_at }}</td>
                    <td>${{ paypalDonate.amount }}</td>
                    <td class="text-capitalize">
                      {{ paypalDonate.cash_in_status }}
                    </td>
                    <td class="fw-bold h6">
                      <span class="ms-5"></span>{{ paypalDonate?.user?.Cash }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header bg-dark">
              <h5 class="text-white">Donates</h5>
            </div>
            <div class="card-body">
              <table class="table table-stirpe table-hover border">
                <thead>
                  <tr>
                    <th class="text-uppercase fw-bold bg-dark text-white">Token</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Payer</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Account</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Date</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">
                      Purchase Cash
                    </th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Amount</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Pack name</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Method</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">Status</th>
                    <th class="text-uppercase fw-bold bg-dark text-white">cash</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="donate in donates.data" :key="donate">
                    <td>{{ donate.token }}</td>
                    <td>{{ donate.payer }}</td>
                    <td>
                      <Link
                        class="text-decoration-underline text-primary"
                        :href="`/administrator-panel/users?search=${donate.account_login}&page=1`"
                      >
                        {{ donate.account_login }}
                      </Link>
                    </td>
                    <td>{{ donate.date_format }}</td>
                    <td>{{ donate.cash }}</td>
                    <td>${{ donate.pack_price }}</td>
                    <td>{{ donate.pack_name }}</td>
                    <td class="text-center">
                      <svg
                        v-if="donate.transaction_type === 'paypal'"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-paypal text-primary"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M14.06 3.713c.12-1.071-.093-1.832-.702-2.526C12.628.356 11.312 0 9.626 0H4.734a.7.7 0 0 0-.691.59L2.005 13.509a.42.42 0 0 0 .415.486h2.756l-.202 1.28a.628.628 0 0 0 .62.726H8.14c.429 0 .793-.31.862-.731l.025-.13.48-3.043.03-.164.001-.007a.35.35 0 0 1 .348-.297h.38c1.266 0 2.425-.256 3.345-.91q.57-.403.993-1.005a4.94 4.94 0 0 0 .88-2.195c.242-1.246.13-2.356-.57-3.154a2.7 2.7 0 0 0-.76-.59l-.094-.061ZM6.543 8.82a.7.7 0 0 1 .321-.079H8.3c2.82 0 5.027-1.144 5.672-4.456l.003-.016q.326.186.548.438c.546.623.679 1.535.45 2.71-.272 1.397-.866 2.307-1.663 2.874-.802.57-1.842.815-3.043.815h-.38a.87.87 0 0 0-.863.734l-.03.164-.48 3.043-.024.13-.001.004a.35.35 0 0 1-.348.296H5.595a.106.106 0 0 1-.105-.123l.208-1.32z"
                        />
                      </svg>
                      <svg
                        v-else
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        class="bi bi-stripe text-purple"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm6.226 5.385c-.584 0-.937.164-.937.593 0 .468.607.674 1.36.93 1.228.415 2.844.963 2.851 2.993C11.5 11.868 9.924 13 7.63 13a7.7 7.7 0 0 1-3.009-.626V9.758c.926.506 2.095.88 3.01.88.617 0 1.058-.165 1.058-.671 0-.518-.658-.755-1.453-1.041C6.026 8.49 4.5 7.94 4.5 6.11 4.5 4.165 5.988 3 8.226 3a7.3 7.3 0 0 1 2.734.505v2.583c-.838-.45-1.896-.703-2.734-.703"
                        />
                      </svg>
                    </td>
                    <td class="text-uppercase">
                      {{ donate.status == "paid" ? "completed" : donate.status }}
                    </td>
                    <td class="text-uppercase text-center">
                      {{ donate.account_purchase?.Cash }}
                    </td>
                  </tr>
                </tbody>
              </table>
              <div class="float-end">
                <ul class="pagination">
                  <li
                    class="page-item cursor-pointer"
                    :class="{
                      active: page.label === currentPage,
                    }"
                    v-for="page in donates.links"
                    :key="page"
                  >
                    <a
                      class="page-link shadow-none"
                      @click="renderPage(page)"
                      v-html="page.label"
                    ></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>
  </layout>
</template>
<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>
