import {
  GetStaticPaths,
  GetStaticProps,
  NextComponentType,
  NextPageContext,
} from "next";
import DefaultErrorPage from "next/error";
import Head from "next/head";
import { useRouter } from "next/router";
import { dehydrate, QueryClient, useQuery } from "react-query";

import { Show } from "../../../components/moviecategory/Show";
import { PagedCollection } from "../../../types/collection";
import { MovieCategory } from "../../../types/MovieCategory";
import { fetch, FetchResponse, getItemPaths } from "../../../utils/dataAccess";
import { useMercure } from "../../../utils/mercure";

const getMovieCategory = async (id: string | string[] | undefined) =>
  id
    ? await fetch<MovieCategory>(`/movie_categories/${id}`)
    : Promise.resolve(undefined);

const Page: NextComponentType<NextPageContext> = () => {
  const router = useRouter();
  const { id } = router.query;

  const {
    data: { data: moviecategory, hubURL, text } = { hubURL: null, text: "" },
  } = useQuery<FetchResponse<MovieCategory> | undefined>(
    ["moviecategory", id],
    () => getMovieCategory(id)
  );
  const moviecategoryData = useMercure(moviecategory, hubURL);

  if (!moviecategoryData) {
    return <DefaultErrorPage statusCode={404} />;
  }

  return (
    <div>
      <div>
        <Head>
          <title>{`Show MovieCategory ${moviecategoryData["@id"]}`}</title>
        </Head>
      </div>
      <Show moviecategory={moviecategoryData} text={text} />
    </div>
  );
};

export const getStaticProps: GetStaticProps = async ({
  params: { id } = {},
}) => {
  if (!id) throw new Error("id not in query param");
  const queryClient = new QueryClient();
  await queryClient.prefetchQuery(["moviecategory", id], () =>
    getMovieCategory(id)
  );

  return {
    props: {
      dehydratedState: dehydrate(queryClient),
    },
    revalidate: 1,
  };
};

export const getStaticPaths: GetStaticPaths = async () => {
  const response = await fetch<PagedCollection<MovieCategory>>(
    "/movie_categories"
  );
  const paths = await getItemPaths(
    response,
    "movie_categories",
    "/moviecategorys/[id]"
  );

  return {
    paths,
    fallback: true,
  };
};

export default Page;
