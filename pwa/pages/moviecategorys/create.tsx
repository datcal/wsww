import { NextComponentType, NextPageContext } from "next";
import Head from "next/head";

import { Form } from "../../components/moviecategory/Form";

const Page: NextComponentType<NextPageContext> = () => (
  <div>
    <div>
      <Head>
        <title>Create MovieCategory</title>
      </Head>
    </div>
    <Form />
  </div>
);

export default Page;
